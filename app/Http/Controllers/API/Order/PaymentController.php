<?php

namespace App\Http\Controllers\API\Order;

use App\Helpers\Helper;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderDetail;
use App\Models\OrderLeftAmount;
use App\Models\Product;
use App\Models\Tax;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Facades\PayPal;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController
{
    public function createOrder(Request $request)
    {
        $request->validate([
            'discount' => 'nullable|numeric|min:0',
            'shop_id' => 'required',
            'valet_tip' => 'nullable|numeric|min:0',
        ]);

        // Ensure the user is authenticated
        $user = Auth::user();
        if (!$user) {
            return Helper::jsonErrorResponse('Unauthorized: User not authenticated', 401);
        }

        $shop_id = $request->shop_id;
        $cartItems = Cart::where('user_id', $user->id)->where('shop_id', $shop_id)->get();

        if ($cartItems->isEmpty()) {
            return Helper::jsonErrorResponse('Cart is empty', 400);
        }

        // Generate order number (unique)
        do {
            $orderNumber = 'ORD-' . rand(10000000, 99999999);
        } while (Order::where('order_number', $orderNumber)->exists());

        $valet_fee = 2.00;
        $platform_fee = 1.00;
        $valet_tip = $request->valet_tip ?? 0.00;
        $discount = $request->discount ?? 0.00;

        // Calculate prices
        $subTotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $subTotal = (string)round($subTotal, 2);
        $taxRate = Tax::first();
        $tax = ($taxRate->tax ?? 0) / 100 * $subTotal;
        $tax = (string)round($tax, 2);
        $total = $subTotal - $discount + $tax + $valet_fee + $platform_fee + $valet_tip;
        $total = (string)round($total, 2);

        // Create the order
        $order = Order::create([
            'user_id' => $user->id,
            'shop_id' => $cartItems->first()->shop_id, // assuming all products in cart are from the same shop
            'order_number' => $orderNumber,
            'payment_method' => 'paypal',
            'payment_id' => null,
            'discount' => $request->discount ?? 0.00,
            'tax' => $tax,
            'tax_percentage' => $taxRate->tax ?? 0,
            'valet_charge' => $valet_fee,
            'valet_tip' => $valet_tip,
            'platform_fee' => $platform_fee,
            'sub_total' => $subTotal,
            'not_found_total' => $subTotal,
            'total_price' => $total,
            'payment_status' => 'unpaid', // Default status
            'status' => 'pending', // Default status
        ]);

        // Initialize PayPal Payment
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.success', ['order_number' => $orderNumber]),
                "cancel_url" => route('order.cancel'),
            ],
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "USD",
                    "value" => $total,
                ],
            ]],
        ]);

        // If PayPal order is created, return approval URL
        if (isset($response['id'])) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return Helper::jsonResponse(true, 'Redirect to PayPal for payment', 200, ['approval_url' => $link['href'] ]);
                }
            }
        }

        return Helper::jsonErrorResponse('Failed to create PayPal order', 500, ['order' => $order]);
    }

    public function paypalSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        // Ensure paymentId is provided
        if (!$request->has('token') || empty($request->token)) {
            return Helper::jsonErrorResponse('Missing or invalid token', 400);
        }

        $token = $request->token;

        // Capture PayPal payment
        $response = $provider->capturePaymentOrder($token);

        if (!isset($response['status']) || $response['status'] !== 'COMPLETED') {
            return Helper::jsonErrorResponse('Payment failed', 500);
        }

        // Fetch order using order_number
        $orderNumber = $request->order_number;
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return Helper::jsonErrorResponse('Order not found', 404);
        }

        // Fetch user from order
        $user = User::find($order->user_id);
        if (!$user) {
            return Helper::jsonErrorResponse('User not found', 404);
        }

        // Fetch cart data again
        $cartItems = Cart::where('user_id', $user->id)->get();
        if ($cartItems->isEmpty()) {
            return Helper::jsonErrorResponse('Cart is empty', 400);
        }

        // Update order in the database
        $order->update([
            'payment_method' => 'paypal',
            'payment_id' => $token,
            'payment_status' => 'paid',
            'status' => 'pending',
        ]);

        // Add items to order_details table
        foreach ($cartItems as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        // Clear cart
        Cart::where('user_id', $user->id)->delete();

        return redirect('/')->with([
            'success' => true,
            'message' => 'Order placed successfully',
            'code' => 200
        ]);

    }

    public function acceptOrder($id)
    {
        $valet = Auth::user(); // Get full user object, not just ID

        if ($valet->role !== 'valet') {
            return Helper::jsonErrorResponse('Valet not allowed', 403);
        }

        $order = Order::find($id);
        if (!$order) {
            return Helper::jsonErrorResponse('Order not found', 404);
        }

        $orderValetCheck = Order::whereNull('valet_id')->where('id', $order->id)->first();
        if (!$orderValetCheck) {
            return Helper::jsonErrorResponse('Order not found', 404);
        }

        // Check if the valet already has a pending order
        $hasPendingOrder = Order::where('status', 'pending')->where('valet_id', $valet->id)->exists();
        if ($hasPendingOrder) {
            return Helper::jsonErrorResponse('Complete previous order first', 403);
        }

        // Assign valet to order
        $order->valet_id = $valet->id;
        $order->save();

        return Helper::jsonResponse(true, 'Order accepted!', 200, $order);
    }

    public function payShopping(Request $request, $orderId)
    {
        $valet = Auth::user(); // Get the authenticated valet
        if ($valet->role !== 'valet') {
            return Helper::jsonErrorResponse('Only valets can process shop owner payments', 403);
        }

        $order = Order::find($orderId);
        if (!$order) {
            return Helper::jsonErrorResponse('Order not found', 404);
        }

        if ($order->valet_id !== $valet->id) {
            return Helper::jsonErrorResponse('You are not assigned to this order', 403);
        }

        if ($order->shopping_payment === 'paid') {
            return Helper::jsonErrorResponse('This shopping payment has already been paid', 400);
        }

        // Validate pay_amount and ensure it's a positive number
        $request->validate([
            'pay_amount' => 'required|numeric|min:0.01', // Ensure the amount is numeric and greater than 0
        ]);

        $orderDetails = OrderDetail::where('order_id', $order->id)->get();
        $orderDetailPrice = 0;

        foreach ($orderDetails as $orderDetail) {
            if ($orderDetail->found_item === 'Yes') {
                $orderDetailPrice += $orderDetail->product->price * $orderDetail->quantity;
            }
        }

        if ($orderDetailPrice <= 0) {
            return Helper::jsonErrorResponse('No valid items found to pay the shop owner', 400);
        }

        // Ensure that the pay_amount is greater than or equal to the orderDetailPrice
        if ($request->get('pay_amount') > $orderDetailPrice) {
            return Helper::jsonErrorResponse('Pay amount cannot be greater than the order amount', 400);
        }

        $valetEmail = $valet->payment_email;
        $amountToPay = $request->pay_amount; // Amount that needs to be sent to the shop owner

        // Process PayPal payout from valet to shop owner
        $paymentSuccess = $this->processPayout($valetEmail, $amountToPay, "Payment for Order #{$order->order_number}");

        if (!$paymentSuccess) {
            return Helper::jsonErrorResponse('Failed to send payment to shop owner', 500);
        }

        // Update order to mark shop payment as completed
        $order->update([
            'shopping_payment' => 'paid',
        ]);

        return Helper::jsonResponse(true, 'Valet shopping payment paid successfully!', 200, ['order' => $order]);
    }


    public function completeOrder($orderId)
    {
        $user = Auth::user();
        $order = Order::find($orderId);

        // Validate the order exists and its status is accepted
        if (!$order) {
            return Helper::jsonErrorResponse('Invalid or unaccepted order', 400);
        }
        // Validate the order exists and its status is accepted
        if ($order->valet_id == null) {
            return Helper::jsonErrorResponse('Invalid or unaccepted order', 400);
        }
        // Validate the order exists and its status is accepted
        if ($order->status == 'completed') {
            return Helper::jsonErrorResponse('Invalid or unaccepted order', 400);
        }

        // Start a database transaction to handle multiple updates safely
        DB::beginTransaction();
        try {
            // Handle not found items and leftover amount
            if ($order->not_found_total > 0) {
                $left_amount = new OrderLeftAmount();
                $left_amount->user_id = $user->id;
                $left_amount->order_id = $order->id;
                $left_amount->amount = $order->not_found_total;
                $left_amount->save();
            }

            // Transfer the payment to the valet (ensure the transfer function works)
            $valetAmount = ($order->valet_charge - $order->valet_charge_extra) + $order->valet_tip;
            $valetEmail = $order->valet->payment_email;

            $paymentSuccess = $this->processPayout($valetEmail, $valetAmount, 'Valet Final Payout');

            // If PayPal payout fails, rollback transaction
            if (!$paymentSuccess) {
                DB::rollBack();
                return Helper::jsonErrorResponse('Error transferring payment to valet', 500);
            }

            // Update the order status and valet payment status
            $order->update([
                'status' => 'completed',
                'valet_payment' => 'paid',
            ]);

            // Commit the transaction
            DB::commit();

            return Helper::jsonResponse(true, 'Order Completed and Valet Paid', 200, $order);
        } catch (\Exception $e) {
            // If something fails, rollback the transaction
            DB::rollBack();
            return Helper::jsonErrorResponse('Error completing the order: ' . $e->getMessage(), 500);
        }
    }

    private function processPayout($email, $amount, $description)
    {
        $provider = PayPal::setProvider();
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->createBatchPayout([
            "sender_batch_header" => [
                "sender_batch_id" => uniqid(),
                "email_subject" => "Payment received!",
                "email_message" => $description,
            ],
            "items" => [[
                "recipient_type" => "EMAIL",
                "receiver" => $email,
                "amount" => ["currency" => "USD", "value" => round($amount, 2)],
                "note" => $description,
            ]],
        ]);

        if (!isset($response['batch_header'])) {
            Log::error('PayPal payout failed', ['response' => $response]);
            return false;
        }
        return true;
    }


}

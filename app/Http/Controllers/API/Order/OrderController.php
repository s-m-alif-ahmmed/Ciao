<?php

namespace App\Http\Controllers\API\Order;

use App\Helpers\Helper;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderLeftAmount;
use App\Models\OrderReceipt;
use App\Models\OrderUserSpendAmount;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Tax;
use App\Notifications\CommonNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController
{
    public function orderInfo($shop_id)
    {
        $user = Auth::user();

        if (!$user) {
            return Helper::jsonErrorResponse('Unauthorized: User not authenticated', 401);
        }

        // Get all products from the cart of the authenticated user
        $cartItems = Cart::where('user_id', $user->id)->with('product');

        $shop = Shop::find($shop_id);

        if (!$shop) {
            return Helper::jsonErrorResponse('Shop not found!', 404);
        }

        // If shop_id is provided, filter cart items by shop_id
        if ($shop_id) {
            $cartItems = $cartItems->where('shop_id', $shop_id);
        } else {
            // If shop_id is not provided, get the first shop_id from cart items
            $firstCartItem = Cart::where('user_id', $user->id)->first();
            if (!$firstCartItem) {
                return Helper::jsonErrorResponse('Cart is empty', 400);
            }
            $shop_id = $firstCartItem->shop_id;
            $cartItems = $cartItems->where('shop_id', $shop_id);
        }

        // Get the filtered cart items
        $cartItems = $cartItems->get();

        if ($cartItems->isEmpty()) {
            return Helper::jsonErrorResponse('No items found for the specified shop', 400);
        }

        // Calculate the subtotal (sum of all product prices in the cart)
        $subTotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Fetch the tax percentage dynamically from the Tax model
        $taxRate = Tax::first(); // Assuming only one tax record exists
        $taxPercentage = $taxRate ? $taxRate->tax : 0; // Default tax value if no record is found

        // Calculate the actual tax amount based on the tax percentage
        $tax = ($taxPercentage / 100) * $subTotal; // Tax = (tax percentage / 100) * subtotal

        // Apply discount if available in request
        $discount = 0.00;

        // Additional fixed charges (e.g., service fees, delivery fees)
        $valet_fee = 2.00;
        $platform_fee = 1.00;

        // Calculate final total
        $total = $subTotal - $discount + $tax + $valet_fee + $platform_fee;

        // Prepare order details response
        $orderDetails = [
            'subTotal' => number_format($subTotal, 2),
            'discount' => number_format($discount, 2),
            'taxPercentage' => $taxPercentage,
            'tax' => number_format($tax, 2),
            'valet_fee' => number_format($valet_fee, 2),
            'platform_fee' => number_format($platform_fee, 2),
            'total' => number_format($total, 2),
            'shop' => $shop,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'number' => $user->number,
            ],
        ];

        return Helper::jsonResponse(true, 'Order summery retrieved successfully!', 200, $orderDetails);
    }

    public function remainingAmountCheck(Request $request)
    {
        $request->validate([
            'discount' => 'required|numeric|min:0',
            'shop_id' => 'required',
        ]);

        $user = Auth::user();
        $user_remaining_amount = 0;

        $shop = Shop::find($request->shop_id);

        if (!$shop) {
            return Helper::jsonErrorResponse('Shop not found!', 404);
        }

        if ($user->role == 'user') {
            $user_left_amount = OrderLeftAmount::where('user_id', $user->id)->sum('amount');
            $user_used_amount = OrderUserSpendAmount::where('user_id', $user->id)->sum('amount');
            $user_remaining_amount = max(0, $user_left_amount - $user_used_amount);
        }

        $cartItems = Cart::where('shop_id', $request->shop_id)->get();

        // Calculate the subtotal (sum of all product prices in the cart)
        $subTotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Fetch the tax percentage dynamically from the Tax model
        $taxRate = Tax::first(); // Assuming only one tax record exists
        $taxPercentage = $taxRate ? $taxRate->tax : 0; // Default tax value if no record is found

        // Calculate the actual tax amount based on the tax percentage
        $tax = ($taxPercentage / 100) * $subTotal; // Tax = (tax percentage / 100) * subtotal

        // Apply discount if available in request
        $discount = $request->discount ?? 0.00;

        $valet_fee = 2.00;
        $platform_fee = 1.00;

        $total = $subTotal + $tax + $platform_fee + $valet_fee - $discount;

        $total = round(number_format($total, 2), 2);;

        if ($request->discount > $user_remaining_amount) {
            return Helper::jsonErrorResponse('Input Amount cannot greater then remaining balance.');
        }

        if ($request->discount > $total) {
            return Helper::jsonErrorResponse('Input Amount cannot greater then order total.');
        }

        $new_total = $total - $request->discount;

        return Helper::jsonResponse(true, 'User details fetched successfully.', 200, [
            'user_remaining_amount' => number_format($user_remaining_amount, 2),
            'total' => number_format($total, 2),
            'discount' => number_format($request->discount, 2),
            'new_total' => number_format($new_total, 2),
        ]);
    }

    /* Get user wise all orders start */
    public function getUserOrders()
    {
        $user = Auth::user();
        if (!$user) {
            return Helper::jsonErrorResponse('Unauthorized: User not authenticated', 401);
        }

        //with thumbnail
        $orders = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with(['orderDetails.product' => function ($query) {
                $query->select(['id', 'name', 'thumbnail']);
            }])
            ->get();

        return Helper::jsonResponse(true, 'Orders fetched successfully!', 200, $orders);
    }
    /* Get user wise all orders end */


    /* Get order details start */
    public function details($id)
    {
        $user = Auth::user();
        if (!$user) {
            return Helper::jsonErrorResponse('Unauthorized: User not authenticated', 401);
        }

        $order = Order::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['orderDetails.product' => function ($query) {
                $query->select(['id', 'name', 'thumbnail']);
            }])
            ->first();

        if (!$order) {
            return Helper::jsonErrorResponse('Order not found', 404);
        }

        return Helper::jsonResponse(true, 'Order details fetched successfully!', 200, $order);
    }
    /* Get order details end */


    public function userAllOrders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with(['orderDetails.product' => function ($query) {
                $query->select(['id', 'name', 'thumbnail']);
            }])
            ->latest()->paginate(10);

        if (!$orders) {
            return Helper::jsonErrorResponse('No order found!', 404);
        }

        return Helper::jsonResponse(true, 'Orders fetched successfully!', 200, $orders, true);
    }


//    Valet All Orders
    public function valetAllOrders($shopId)
    {
        $user = Auth::user();
        if (!$user->role == 'valet') {
            return Helper::jsonErrorResponse('Unauthorized: Valet not authenticated', 401);
        }

        $shop = Shop::find($shopId);
        if (!$shop) {
            return Helper::jsonErrorResponse('Shop not found', 404);
        }

        $orders = Order::whereNull('valet_id')
            ->where('shop_id', $shopId)
            ->where('payment_status', 'paid')
            ->where('status', 'pending')
            ->with([
                'orderDetails.product' => function ($query) {
                    $query->select(['id', 'name', 'thumbnail']);
                },
                'shop' => function ($query) {
                    $query->select(['id', 'name', 'latitude', 'longitude']);
                }
            ])
            ->latest()->paginate(10);

        if (!$orders) {
            return Helper::jsonErrorResponse('No order found!', 404);
        }

        // Custom response to include shop details outside of "data"
        return response()->json([
            'status' => true,
            'message' => 'Orders fetched successfully!',
            'code' => 200,
            'shop' => [
                'id' => $shop->id,
                'name' => $shop->name
            ],
            'data' => $orders
        ]);
    }

//    Valet All Orders
    public function valetUserAllOrders()
    {
        $user = Auth::user();
        if (!$user->role == 'valet') {
            return Helper::jsonErrorResponse('Unauthorized: Valet not authenticated', 401);
        }

        $orders = Order::where('valet_id', $user->id)
            ->where('payment_status', 'paid')
            ->with([
                'orderDetails.product' => function ($query) {
                    $query->select(['id', 'name', 'thumbnail']);
                },
                'shop' => function ($query) {
                    $query->select(['id', 'name', 'latitude', 'longitude']);
                }
            ])
            ->latest()->paginate(10);

        if (!$orders) {
            return Helper::jsonErrorResponse('No order found!', 404);
        }

        return Helper::jsonResponse(true, 'Orders fetched successfully!', 200, $orders, true);
    }

//    Valet All Orders
    public function valetPendingOrder()
    {
        $user = Auth::user();
        if (!$user->role == 'valet') {
            return Helper::jsonErrorResponse('Unauthorized: Valet not authenticated', 401);
        }

        $order = Order::where('valet_id', $user->id)
            ->where('payment_status', 'paid')
            ->where('status', 'pending')
            ->with([
                'orderDetails.product' => function ($query) {
                    $query->select(['id', 'name', 'thumbnail']);
                },
                'shop' => function ($query) {
                    $query->select(['id', 'name', 'latitude', 'longitude']);
                }
            ])
            ->first();

        // Ensuring response has 'data' as null when no order found
        if (!$order) {
            return response()->json([
                'success' => true,
                'message' => 'No order found!',
                'code' => 200,
                'data' => null
            ], 200);
        }

        return Helper::jsonResponse(true, 'Orders fetched successfully!', 200, $order);
    }

    public function valetProductCheck($id)
    {
        $valet = Auth::user();
        if (!$valet || $valet->role !== 'valet') { // Fixed role check
            return Helper::jsonErrorResponse('Unauthorized: User not authenticated', 401);
        }

        $order = Order::where('id', $id)
            ->where('valet_id', $valet->id)
            ->with(['orderDetails.product:id,name,thumbnail'])
            ->first();

        if (!$order) {
            return Helper::jsonErrorResponse('Order not found', 404);
        }

        // Check if any orderDetails->found_item == 'None'
        $hasNoneItem = $order->orderDetails->contains('found_item', 'None');

        if (!$hasNoneItem) {
            return response()->json([
                'success' => true,
                'message' => 'Some items are still remain unchecked!',
                'code' => 200,
                'data' => [
                    'type' => false
                ],
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order details fetched successfully!',
            'code' => 200,
            'data' => [
                'type' => true
            ],
        ], 200);

    }

    /* Get order details end */

    public function acceptedOrders()
    {
        $user = Auth::id();
        $orders = Order::where('valet_id', $user)
            ->with([
                'orderDetails.product' => function ($query) {
                    $query->select(['id', 'name', 'thumbnail']);
                },
                'shop' => function ($query) {
                    $query->select(['id', 'name', 'latitude', 'longitude']);
                }
            ])
            ->latest()->paginate(10);

        if (!$orders) {
            return Helper::jsonErrorResponse('No order found!', 404);
        }

        return Helper::jsonResponse(true, 'Orders found successfully!', 200, $orders, true);
    }

    public function productFound(Request $request, $order_id, $id)
    {
        $request->validate([
            'found_item' => 'required|in:Yes,No'
        ]);

        $user = Auth::id();

        $product = Product::find($id);
        if (!$product) {
            return Helper::jsonErrorResponse('No product found!', 404);
        }

        $order = Order::where('id', $order_id)->where('valet_id', $user)->first();
        if (!$order) {
            return Helper::jsonErrorResponse('No order found!', 404);
        }

        $orderProducts = OrderDetail::where('order_id', $order_id)->where('product_id', $product->id)->get();

        if ($orderProducts->isEmpty()) {
            return Helper::jsonErrorResponse('No product found in order!', 404);
        }

        $notFoundTotal = $order->not_found_total ?? 0;

        // Update each record in the collection
        foreach ($orderProducts as $orderProduct) {

            if ($orderProduct->found_item == 'None') {

                if ($request->found_item == 'No') {
                    $orderProduct->update(['found_item' => $request->found_item]);
                    $notFoundTotal -= 0;
                }elseif ($request->found_item == 'Yes'){
                    $orderProduct->update(['found_item' => $request->found_item]);
                    $notFoundTotal -= $orderProduct->price * $orderProduct->quantity;
                }else{
                    return Helper::jsonResponse(true, 'Order found failed!', 500, $orderProducts);
                }
            }else{
                return Helper::jsonErrorResponse('No product found in order!', 404);
            }
        }

        if ($notFoundTotal >= 0) {
            $order->update(['not_found_total' => $notFoundTotal]);
        }

        return Helper::jsonResponse(true, 'Order items updated successfully!', 200, $orderProducts);
    }

    public function orderReceipt(Request $request)
    {
        // Validate request
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
        ]);

        // Find the order or fail
        $order = Order::findOrFail($request->order_id);
        if (!$order) {
            return Helper::jsonErrorResponse('No order found!', 404);
        }

        // Check if images are present in the request
        if ($request->hasFile('images')) {
            $images = $request->file('images');

            foreach ($images as $image) {
                // Generate unique name for the image
                $imageName = time() . '_' . uniqid('', true) . '.' . $image->extension();
                // Use helper function to upload the image and get the path
                $path = Helper::fileUpload($image, 'Order/Receipts', $imageName);

                $receipt = new OrderReceipt();
                $receipt->order_id = $order->id;
                $receipt->image = $path;
                $receipt->save();
            }

            // Return success response with image paths
            return Helper::jsonResponse(true, 'Order receipt saved successfully!', 200);
        } else {
            // If no images are provided, return error
            return Helper::jsonResponse(false, 'Order receipt save failed! No images uploaded.', 404);
        }
    }
}

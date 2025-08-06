<?php

namespace App\Http\Controllers\API\Carts;

use App\Helpers\Helper;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Shop;
use App\Notifications\CommonNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController
{
    //All Cart Item
    public function allCartItem($id)
    {
        $user = Auth::user();

        if (!$user) {
            return Helper::jsonErrorResponse('Unauthorized: User not authenticated', 401);
        }

        $shop = Shop::find($id);

        if (!$shop) {
            return Helper::jsonResponse(false, 'Shop not found!', 404, []);
        }

        $carts = Cart::where('user_id', $user->id)
            ->where('shop_id', $shop->id)
            ->select('id', 'user_id', 'shop_id', 'product_id', 'quantity', 'note')
            ->with('product')->get();

        if ($carts->isEmpty()) {
            return Helper::jsonResponse(false, 'No cart items found!', 200, []);
        }

        return Helper::jsonResponse(true, 'All Cart Items', 200, $carts);
    }


    // Add product to cart
    public function addToCart(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return Helper::jsonErrorResponse($validator->errors()->first(), 422);
        }

        $user = Auth::user();

        $shop = Shop::where('id', $id)->first();
        if (!$shop) {
            return Helper::jsonResponse(false, 'Shop not found!', 404, []);
        }
        $product = Product::where('id', $request->product_id)->where('shop_id', $shop->id)->first();

        if (!$product) {
            return Helper::jsonErrorResponse('Product not found!', 404);
        }

        // Check if the product is already in the user's cart
        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('shop_id', $product->shop_id)
            ->first();

        if ($cartItem) {
            // If the cart item exists, check if it's older than 24 hours
            //for day = subDays(3) : mean 3 days
            //for Hour = subHours
            //for minute = subMinutes
            //for second = subSeconds
            if ($cartItem->created_at->lt(now()->subHours(24))) {
                // If it's older than 24 seconds, delete it
                $cartItem->delete();
                return Helper::jsonErrorResponse('Cart item expired and removed!', 410); // 410 Gone
            }

            // If the cart item is not expired, update the quantity
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // If no cart item, add new cart entry
            $cartItem = Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'shop_id' => $product->shop_id,
                'quantity' => $request->quantity,
            ]);
        }

          // **store Notification in DB**
          $details = [
            'subject' => 'Add to Cart Successfully!',
            'message' => 'You have successfully added ' . $product->name . ' to your cart.',
        ];
        $user->notify(new CommonNotification($details));

        return Helper::jsonResponse(true, 'Product added to cart successfully!', 200, [
            'name' => $product->name,
            'price' => $product->price,
            'thumbnail' => $product->thumbnail,
            'description' => $product->description,
            'quantity' => $cartItem->quantity // Make sure to use $cartItem here
        ]);
    }

    //Cart qty will be  plus
    public function quantityPlus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return Helper::jsonErrorResponse($validator->errors()->first(), 422);
        }

        $user = Auth::user();
        $cartItem = Cart::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$cartItem) {
            return Helper::jsonErrorResponse('Product not found in cart!', 404);
        }

        $cartItem->quantity += $request->quantity;

        $cartItem->save();

        return Helper::jsonResponse(true, 'Cart item quantity updated successfully!', 200, [
            'cart_item' => $cartItem
        ]);
    }

    // //Cart qty will be minus
    public function quantityMinus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return Helper::jsonErrorResponse($validator->errors()->first(), 422);
        }
        $user = Auth::user();

        $cartItem = Cart::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$cartItem) {
            return Helper::jsonErrorResponse('Product not found in cart!', 404);
        }

        if ($cartItem->quantity <= $request->quantity) {
            $cartItem->quantity = $cartItem->quantity;
        } else {
            $cartItem->quantity -= $request->quantity;
        }

        $cartItem->save();

        return Helper::jsonResponse(true, 'Cart item quantity updated successfully!', 200, [
            'cart_item' => $cartItem
        ]);
    }

    //remove from cart
    public function removeFromCart($id)
    {
        $user = Auth::user();
        $cartItem = Cart::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$cartItem) {
            return Helper::jsonErrorResponse('Product not found in cart!', 404);
        }

        $cartItem->delete();

        return Helper::jsonResponse(true, 'Product removed from cart successfully!', 200 );
    }

    //add note
    public function addnote(Request $request, $shop_id, $id, $product_id)
    {
        $validator = Validator::make($request->all(), [
            'note' => 'required|string',
        ]);

        if ($validator->fails()) {
            return Helper::jsonErrorResponse($validator->errors()->first(), 422);
        }

        $user = Auth::id();
        $cartItem = Cart::where('user_id', $user)
            ->where('product_id', $product_id)
            ->where('shop_id', $shop_id)
            ->where('id', $id)
            ->first();

        if (!$cartItem) {
            return Helper::jsonErrorResponse('Product not found in cart!', 404);
        }

        // Correctly update the note
        $cartItem->update(['note' => $request->note]);

        return Helper::jsonResponse(true, 'Note added successfully!', 200);
    }

}

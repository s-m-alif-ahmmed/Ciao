<?php

namespace App\Http\Controllers\API\Bookmark;

use App\Helpers\Helper;
use App\Models\Favourite;
use App\Models\Product;
use App\Models\User;
use App\Notifications\CommonNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddToFavouriteController
{
    public function addToFavourite(Request $request)
    {

        // Check if user is authenticated
        if (!auth()->check()) {
            return Helper::jsonErrorResponse('Unauthorized: User not authenticated', 401);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return Helper::jsonErrorResponse('Add to Favourite Validation failed', 422, $validator->errors()->toArray());
        }

        $user = Auth::user();
        $productId = $request->product_id;

        // Check if the product is already in favourites
        $favourite = Favourite::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($favourite) {
            // If exists, remove from favourites
            $favourite->delete();

            // **store Notification in DB**
            $details = [
                'subject' => 'Removed from favourites!',
                'message' => Product::find($productId)->name . ' removed from favourites successfully.',
            ];
            $user->notify(new CommonNotification($details));

            return Helper::jsonResponse(false, 'Removed from favourites', 200, ['is_favourite' => false]);
        } else {
            // Else, add to favourites
            Favourite::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);

            // **store Notification in DB**
            $details = [
                'subject' => 'Added to favourites!',
                'message' =>  Product::find($productId)->name.' added to favourites successfully.',
            ];
            $user->notify(new CommonNotification($details));

            // Return success response
            return Helper::jsonResponse(true,'Added to favourites', 200, ['is_favourite' => true]);
        }
    }

    public function getFavourite(Request $request)
    {
        try {
            $user = Auth::user();
            $favourites = Favourite::where('user_id', $user->id)
                ->with([
                    'product' => function ($query) {
                        $query->select('id', 'name', 'quantity', 'price');
                    },
                    'product.images' => function ($query) {
                        $query->select('id', 'product_id', 'image_path');

                    }
                ])
                ->select('id', 'user_id', 'product_id')
                ->get();

            if ($favourites->isEmpty()) {
                return Helper::jsonResponse(false, 'No Favourite list found!', 200, []);
            }

            return Helper::jsonResponse(true, 'Favourite list fetched successfully', 200, $favourites);
        } catch (\Exception $e) {
            return Helper::jsonResponse(false, 'Error fetching favourite list', 500, []);
        }
    }

}

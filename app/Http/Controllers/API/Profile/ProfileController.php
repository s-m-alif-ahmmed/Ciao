<?php


namespace App\Http\Controllers\API\Profile;

use App\Helpers\Helper;
use App\Models\User;
use App\Notifications\CommonNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController
{
    public function updateProfile(Request $request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return Helper::jsonErrorResponse('Unauthorized: User not authenticated', 401);
        }

        $authenticatedUser = auth()->user(); // Directly get authenticated user

        // Validation rules
        $validator = Validator::make($request->all(), [
            'name'   => 'nullable|string|max:255',
            'email'  => 'nullable|email|max:255|unique:users,email,' . $authenticatedUser->id,
            'phone_number' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return Helper::jsonErrorResponse('Profile Update Validation failed', 422, $validator->errors()->toArray());
        }

        // Update user data
        $authenticatedUser->update([
            'name'   => $request->name ?? $authenticatedUser->name,
            'email'  => $request->email ?? $authenticatedUser->email,
            'number' => $request->phone_number ?? $authenticatedUser->number,
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($authenticatedUser->avatar) {
                Helper::fileDelete($authenticatedUser->avatar);
            }
            $avatarPath = Helper::fileUpload($request->file('avatar'), 'Profile', time() . '_' . $request->file('avatar')->getClientOriginalName());
            $authenticatedUser->avatar = $avatarPath;
            $authenticatedUser->save();
        }

        // **store Notification in DB**
        $details = [
            'subject' => 'Profile Updated Successfully!',
            'message' => 'Your profile information has been updated successfully.',
        ];

        $authenticatedUser->notify(new CommonNotification($details));

        return Helper::jsonResponse(true, 'Profile updated successfully', 200, $authenticatedUser->only(['name', 'email', 'number', 'avatar']));
    }

    public function updatePassword(Request $request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return Helper::jsonErrorResponse('Unauthorized: User not authenticated', 401);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return Helper::jsonErrorResponse('Password Update Validation failed', 422, $validator->errors()->toArray());
        }

        $authenticatedUser = auth()->user(); // Directly get authenticated user

        if (!Hash::check($request->current_password, $authenticatedUser->password)) {
            return Helper::jsonErrorResponse('Current password does not match', 422);
        }

        $authenticatedUser->password = Hash::make($request->password);
        $authenticatedUser->save();

         // **store Notification in DB**
         $details = [
            'subject' => 'Password Updated Successfully!',
            'message' => 'Your Password information has been updated successfully.',
        ];

        $authenticatedUser->notify(new CommonNotification($details));


        return Helper::jsonResponse(true, 'Password updated successfully', 200);
    }

    public function addPayment(Request $request)
    {
        if (!auth()->check()) {
            return Helper::jsonErrorResponse('Unauthorized: User not authenticated', 401);
        }

        $user = auth()->user();

        $user->payment_email = $request->payment_email;
        $user->save();

        return Helper::jsonResponse(true, 'Payment added successfully', 200);
    }


}

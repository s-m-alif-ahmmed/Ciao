<?php

namespace App\Http\Controllers\API\Login;

use App\Helpers\Helper;
use App\Models\OrderLeftAmount;
use App\Models\OrderUserSpendAmount;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\Auth;

class LoginController
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            return Helper::jsonErrorResponse('The provided credentials do not match our records.',401,[
                'email' => 'The provided credentials do not match our records.'
            ]);
        }

        $user = Auth::user();

        // Check if email is verified
        if ($user->email_verified_at === null) {
            $otp = (new Otp)->generate($user->email, 'numeric', 6, 60);
            $mailType = 'verify'; // Define the mail type
            $message = 'Verify Your Email Address';

            \Mail::to($user->email)->send(new \App\Mail\OTP($otp->token, $user, $message, $mailType));

            return Helper::jsonErrorResponse('Email not verified. OTP sent to your email.', 403, []);
        }

        // Prepare data to return based on the user's role
        $responseData = [
            'token_type' => 'Bearer',
            'token' => $user->createToken('AuthToken')->plainTextToken,
            'data' => [
                'user' => $user,
            ]
        ];

        // If the user is a valet, include valet profile data and images
        if ($user->role == 'valet') {
            $valetProfile = $user->valetProfile;
            $responseData['data']['valetProfile'] = $valetProfile;
            if ($valetProfile && $valetProfile->images && $valetProfile->images->count() > 0) {
                $responseData['data']['id_card'] = $valetProfile->images->first();
            }
        }

        // Return the response
        return Helper::jsonResponse(true, 'Login Successful', 200, $responseData);

    }

    public function logout(Request $request)
    {
        try {
            // Revoke the current user’s token
            $request->user()->currentAccessToken()->delete();
            // Return a response indicating the user was logged out
            return Helper::jsonResponse(true, 'Logged out successfully.', 200, []);
        }catch (\Exception $exception){
            return Helper::jsonErrorResponse($exception->getMessage(),401,[]);
        }
    }

    public function user()
    {
        // Get authenticated user and eager load valetProfile
        $user = Auth::user()->load('valetProfile');

        if ($user->role == 'user') {
            $user_left_amount = OrderLeftAmount::where('user_id', $user->id)->sum('amount');
            $user_used_amount = OrderUserSpendAmount::where('user_id', $user->id)->sum('amount');
            $user_remaining_amount = $user_left_amount - $user_used_amount;
            if ($user_remaining_amount < 0) {
                $user_remaining_amount = 0;
            }
        }else{
            $user_remaining_amount = 0;
        }

        // Structuring response data properly
        $data = [
            'user' => $user,
            'remaining_amount' => $user_remaining_amount,
        ];

        // Return the response with user data including valetProfile
        return Helper::jsonResponse(true, 'User details fetched successfully.', 200, $data);
    }

    public function userRemainingAmount()
    {
        $user = Auth::user();

        if ($user->role == 'user') {
            $user_left_amount = OrderLeftAmount::where('user_id', $user->id)->sum('amount');
            $user_used_amount = OrderUserSpendAmount::where('user_id', $user->id)->sum('amount');
            $user_remaining_amount = $user_left_amount - $user_used_amount;
            if ($user_remaining_amount < 0) {
                $user_remaining_amount = 0;
            }
        }else{
            $user_remaining_amount = 0;
        }

        // Structuring response data properly
        $data = [
            'remaining_amount' => $user_remaining_amount,
        ];

        // Return the response with user data including valetProfile
        return Helper::jsonResponse(true, 'User details fetched successfully.', 200, $data);

    }

    public function deleteAccount()
    {
        $link = env('APP_URL');

        return Helper::jsonResponse(true, 'Visit this url for delete you account!', 200, $link);
    }

    public function deleteUser()
    {
        $user = auth::user();

        if (!$user) {
            return Helper::jsonErrorResponse('Authentication Error', 401);
        }

        $user->delete();

        return Helper::jsonResponse(true, 'Your account delete successfully!', 200);
    }


}

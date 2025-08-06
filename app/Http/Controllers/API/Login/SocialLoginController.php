<?php

namespace App\Http\Controllers\API\Login;

use App\Helpers\Helper;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController
{

    public function SocialLogin(Request $request)
    {
        // Custom validation
        $validator = Validator::make($request->all(), [
            'provider' => 'required|in:google,facebook',
            'token' => 'required',
        ]);
    
        // If validation fails, return custom error response using Helper::jsonErrorResponse
        if ($validator->fails()) {
            return Helper::jsonErrorResponse('Validation Failed', 422, ['errors' => $validator->errors()]);
        }
    
        try {
            // provider = google or facebook
            $socialUser = Socialite::driver($request->provider)->stateless()->userFromToken($request->token);
    
            if ($socialUser) {
                // Check if user exists in the database
                $user = User::where('email', $socialUser->getEmail())->first();
    
                if (!$user) {
                    // Generate a random password
                    $password = Str::random(16);
    
                    // Create new user
                    $user = User::create([
                        'name' => $socialUser->getName(),
                        'email' => $socialUser->getEmail(),
                        'avatar' => $socialUser->getAvatar(),
                        'email_verified_at' => Carbon::now(),  // Setting email_verified_at to current time
                        'password' => Hash::make($password),
                        $request->provider . '_id' => $socialUser->getId(), // google_id or facebook_id
                    ]);
                }
    
                // Generate Sanctum Token
                $token = $user->createToken('auth_token')->plainTextToken;
    
                // Return success response using Helper::jsonResponse
                return Helper::jsonResponse(true, "Login Successfully via " . ucfirst($request->provider), 200, [
                    'token' => $token,
                    'user' => $user,
                ]);
            } else {
                return Helper::jsonErrorResponse('Invalid or Expired Token', 422);
            }
        } catch (Exception $e) {
            // Return error response using Helper::jsonErrorResponse in case of exception
            return Helper::jsonErrorResponse('Something went wrong', 500, [$e->getMessage()]);
        }
    }
    
}

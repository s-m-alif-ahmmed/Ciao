<?php

namespace App\Http\Controllers\API\Login;

use App\Helpers\Helper;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\ValetProfile;
use Carbon\Carbon;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController
{
    public function register(RegisterRequest $request)
    {
        try {
            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                $avatar = Helper::fileUpload($request->file('avatar'), 'user/avatar', getFileName($request->file('avatar')));
            } else {
                $avatar = null;
            }

            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'avatar' => $avatar,
            ]);

            //send email verification otp
            $this->send_otp($user);

            DB::commit();
            return Helper::jsonResponse(true, 'Register successfully', 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }

    public function send_otp(User $user, $mailType = 'verify')
    {
        $otp  = (new Otp)->generate($user->email, 'numeric', 6, 60);
        $message = $mailType === 'verify' ? 'Verify Your Email Address' : 'Reset Your Password';
        Mail::to($user->email)->send(new \App\Mail\OTP($otp->token, $user, $message, $mailType));
    }

    public function resend_otp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        try {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $this->send_otp($user);
                return Helper::jsonResponse(true, 'OTP send successfully.', 201);
            } else {
                return Helper::jsonErrorResponse('Email not found', 404);
            }
        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }

    public function register_verify_otp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'otp' => 'required|string|digits:6',
        ]);
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return Helper::jsonErrorResponse('Email not found', 404);
            }

            if ($user->email_verified_at !== null) {
                return Helper::jsonErrorResponse('Email already verified', 404);
            }

            $verify = (new Otp)->validate($request->email, $request->otp);
            if ($verify->status) {
                $user->email_verified_at = now();
                $user->save();
                return Helper::jsonResponse(true, 'Email verified successfully', 200);
            } else {
                return Helper::jsonErrorResponse($verify->message, 404);
            }
        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }

    public function forgot_password(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return Helper::jsonErrorResponse('Email not found', 404);
            }
            $this->send_otp($user, 'forget');
            return Helper::jsonResponse(true, 'OTP send successfully.', 201);
        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }

    public function verify_otp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'otp' => 'required|string|digits:6',
        ]);

        $verify = (new Otp)->validate($request->email, $request->otp);
        if ($verify->status) {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return Helper::jsonErrorResponse('Email not found', 404);
            }
            $user->reset_password_token = Str::random(15);
            $user->reset_password_token_exp = Carbon::now()->minutes(15);
            $user->save();
            return Helper::jsonResponse(true, 'Email verified successfully', 200, [
                'token' => $user->reset_password_token,
            ]);
        } else {
            return Helper::jsonErrorResponse($verify->message, 404);
        }
    }

    public function reset_password(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        try {
            $user = User::where('email', $request->email)->where('reset_password_token', $request->token)->first();

            if (!$user) {
                return Helper::jsonErrorResponse('Invalid Token', 404);
            }

            if ($user->reset_password_token_exp > Carbon::now()) {
                return  Helper::jsonErrorResponse('Token expired', 404);
            }

            $user->password = bcrypt($request->password);
            $user->reset_password_token = null;
            $user->reset_password_token_exp = null;
            $user->save();
            return Helper::jsonResponse(true, 'Password reset successfully', 200);
        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 404);
        }
    }


    /* valet register function start */
    public function valetRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone_number' => 'nullable|string|max:20',
            'country' => 'required|string|max:255',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return Helper::jsonErrorResponse($validator->errors()->first(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'number' => $request->phone_number,
            'country' => $request->country,
            'password' => Hash::make($request->password),
            'role' => 'valet'
        ]);

        ValetProfile::create([
           'user_id' => $user->id,
           'paper_work' => null,
            'user_name' => null,
            'meet_requirement' => 0,
            'status' => 'Pending'
        ]);

        //send email verification otp
        $this->send_otp($user);

        return Helper::jsonResponse(true, 'Register Successful', 200);
    }
    /* valet register function end */
}

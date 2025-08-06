<?php

use App\Http\Controllers\Web\Backend\Settings\MailSettingController;
use App\Http\Controllers\Web\Backend\Settings\MapSettingController;
use App\Http\Controllers\Web\Backend\Settings\ProfileController;
use App\Http\Controllers\Web\Backend\Settings\StripeSettingController;
use App\Http\Controllers\Web\Backend\Settings\SystemSettingController;
use Illuminate\Support\Facades\Route;

//! Route for Profile Settings
Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'index')->name('profile.setting');
    Route::patch('/update-profile', 'UpdateProfile')->name('update.profile');
    Route::put('/update-profile-password', 'UpdatePassword')->name('update.Password');
    Route::post('/update-profile-picture', 'UpdateProfilePicture')->name('update.profile.picture');
});

//! Route for System Settings
Route::controller(SystemSettingController::class)->group(function () {
    Route::get('/system-setting', 'index')->name('system.index');
    Route::patch('/system-setting', 'update')->name('system.update');
});

//! Route for Mail Settings
Route::controller(MailSettingController::class)->group(function () {
    Route::get('/mail-setting', 'index')->name('mail.setting');
    Route::patch('/mail-setting', 'update')->name('mail.update');
});

//! Route for Stripe Settings
Route::controller(StripeSettingController::class)->group(function () {
    Route::get('/stripe-setting', 'index')->name('stripe.index');
    Route::get('/google-setting', 'google')->name('google.index');
    Route::patch('/credentials-setting', 'update')->name('credentials.update');
});

//! Route for Stripe Settings
Route::controller(MapSettingController::class)->group(function () {
    Route::get('/map-setting', 'index')->name('map.index');
    Route::patch('/map-setting', 'update')->name('map.update');
});

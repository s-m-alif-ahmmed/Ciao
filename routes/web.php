<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\DynamicPageController;
use App\Http\Controllers\Web\Backend\UserController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role == 'admin') {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('user.delete');
        }
    } else {
        return redirect('/login');
    }
});

Route::middleware(['web', 'auth', 'user','valet'])->group( function () {

    Route::get('/user/delete',[UserController::class,'userDelete'])->name('user.delete');
    Route::post('/user/delete/confirm',[UserController::class,'userDeleteConfirm'])->name('user.delete.confirm');

});

Route::get('/page/{slug}', [DynamicPageController::class, 'page']);

require __DIR__.'/auth.php';

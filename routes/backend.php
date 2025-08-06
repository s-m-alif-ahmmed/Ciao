<?php

use App\Http\Controllers\Web\Backend\CategoryController;
use App\Http\Controllers\Web\Backend\CmsController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\DynamicPageController;
use App\Http\Controllers\Web\Backend\OrderController;
use App\Http\Controllers\Web\Backend\ProductController;
use App\Http\Controllers\Web\Backend\ShopController;
use App\Http\Controllers\Web\Backend\SubCategoryCreateController;
use App\Http\Controllers\Web\Backend\TaxController;
use App\Http\Controllers\Web\Backend\UserController;
use Illuminate\Support\Facades\Route;



//! Route for Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//! Route for Users Page
Route::controller(UserController::class)->group(function () {
    Route::get('/user', 'index')->name('user.index');
    Route::get('/user/valet-approved', 'valet')->name('user.valet.index');
    Route::get('/user/valet', 'valetApproved')->name('user.valet-approved.index');
    Route::get('/user/valet/details/{id}', 'show')->name('user.valet.details');
    Route::get('/user/status/{id}', 'status')->name('user.status');
    Route::post('/update-valet-status', 'updateValetStatus')->name('update.valet.status');
    Route::delete('/user/destroy/{id}', 'destroy')->name('user.destroy');
});

//route for products
Route::controller(ProductController::class)->group(function () {
    Route::post('/products/duplicate', 'duplicate')->name('products.duplicate');
    Route::post('/products/duplicate-shop', 'duplicateMultipleShops')->name('products.duplicate-shop');
    Route::Delete('/products/bulk-delete', 'bulkDelete')->name('products.bulk-delete');
    Route::get('/product/create', 'create')->name('product.create');
    Route::post('/product/store', 'store')->name('product.store');
    Route::get('/product/edit/{id}', 'edit')->name('product.edit');
    Route::put('/product/update/{id}', 'update')->name('product.update');
    Route::get('/product/status/{id}', 'status')->name('product.status');
    Route::delete('/product/destroy/{id}', 'destroy')->name('product.destroy');
    Route::get('/product/details/{id}', 'details')->name('product.details');
    //Ajax load for create products page
    Route::get('/get-subcategories', 'getSubcategories')->name('get.subcategories');
    Route::post('/product/update-shop', 'updateShop')->name('product.updateShop');
    Route::get('/product/{id}', 'index')->name('product.index');
});


//route for sub-categories
Route::controller(SubCategoryCreateController::class)->group(function () {
    Route::get('/sub-category', 'index')->name('subcategory.index');
    Route::get('/sub-category/create', 'create')->name('subcategory.create');
    Route::post('/sub-category/store', 'store')->name('subcategory.store');
    Route::get('/sub-category/edit/{id}', 'edit')->name('subcategory.edit');
    Route::put('/sub-category/update/{id}', 'update')->name('subcategory.update');
    Route::get('/sub-category/status/{id}', 'status')->name('subcategory.status');
    Route::delete('/sub-category/delete/{id}', 'destroy')->name('subcategory.destroy');
});

//route for shops
Route::controller(ShopController::class)->group(function () {
    Route::get('/shop', 'index')->name('shop.index');
    Route::get('/shop/create', 'create')->name('shop.create');
    Route::post('/shop/store', 'store')->name('shop.store');
    Route::get('/shop/edit/{id}', 'edit')->name('shop.edit');
    Route::put('/shop/update/{id}', 'update')->name('shop.update');
    Route::get('/shop/status/{id}', 'status')->name('shop.status');
    Route::delete('/shop/destroy/{id}', 'destroy')->name('shop.destroy');
});

//route for categories
Route::controller(CategoryController::class)->group(function () {
    Route::get('/category', 'index')->name('category.index');
    Route::get('/category/create', 'create')->name('category.create');
    Route::post('/category/store', 'store')->name('category.store');
    Route::get('/category/edit/{id}', 'edit')->name('category.edit');
    Route::put('/category/update/{id}', 'update')->name('category.update');
    Route::get('/category/status/{id}', 'status')->name('category.status');
    Route::delete('/category/destroy/{id}', 'destroy')->name('category.destroy');
});

//route for tax
Route::controller(TaxController::class)->group(function () {
    Route::get('taxes', 'index')->name('taxes.index');
    Route::get('taxes/create', 'create')->name('taxes.create');
    Route::post('taxes/store', 'store')->name('taxes.store');
    Route::get('taxes/edit/{id}', 'edit')->name('taxes.edit');
    Route::post('taxes/update/{id}', 'update')->name('taxes.update');
    Route::delete('taxes/del/{id}', 'destroy')->name('taxes.destroy');
    Route::get('taxes/status/{id}', 'status')->name('taxes.status');
});

//route for orders
Route::controller(OrderController::class)->group(function () {
    Route::get('/orders/list', 'index')->name('orders.index');
    Route::get('/orders/list-pending', 'indexPending')->name('orders.pending.index');
    Route::get('/orders/list-valet-waiting', 'indexValetPending')->name('orders.valet.waiting.index');
    Route::get('/orders/single/details/{id}', 'details')->name('orders.details');
    Route::get('/orders/show/{id}', 'show')->name('orders.show');
    Route::post('/orders/status/{id}', 'changeStatus')->name('orders.status');

});

//route for Banner
Route::controller(CmsController::class)->group(function () {
    Route::get('/banner/list', 'index')->name('banner.index');
    Route::get('/banner/create', 'create')->name('banner.create');
    Route::post('/banner/store', 'store')->name('banner.store');
    Route::get('/banner/edit/{id}', 'edit')->name('banner.edit');
    Route::post('/banner/update/{id}', 'update')->name('banner.update');
    Route::get('/banner/status/{id}', 'status')->name('banner.status');
    Route::delete('/banner/delete/{id}', 'destroy')->name('banner.destroy');

});

//route for Dynamic page
Route::controller(DynamicPageController::class)->group(function () {
    Route::get('/dynamic', 'index')->name('dynamic.index');
    Route::get('/dynamic/edit/{id}', 'edit')->name('dynamic.edit');
    Route::post('/dynamic/update/{id}', 'update')->name('dynamic.update');
    Route::get('/dynamic/status/{id}', 'status')->name('dynamic.status');
});


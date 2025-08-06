<?php

use App\Http\Controllers\API\Login\RegisterController;
use App\Http\Controllers\API\Login\SocialLoginController;
use App\Http\Controllers\API\Setting\SystemSettingController;
use App\Http\Controllers\API\Login\LoginController;
use App\Http\Controllers\API\Banner\CmsController;
use App\Http\Controllers\API\Bookmark\AddToFavouriteController;
use App\Http\Controllers\API\Carts\CartController;
use App\Http\Controllers\API\Catlist\CategoryListController;
use App\Http\Controllers\API\DynamicPage\DynamicPageController;
use App\Http\Controllers\API\Notification\NotificationController;
use App\Http\Controllers\API\Order\OrderController;
use App\Http\Controllers\API\Product\ProductListController;
use App\Http\Controllers\API\Profile\ProfileController;
use App\Http\Controllers\API\Shop\ShopListController;
use App\Http\Controllers\API\SubcatList\SubcategoryListController;
use App\Http\Controllers\API\ValetProfile\ValetProfileController;
use App\Http\Controllers\API\Order\PaymentController;
use App\Http\Controllers\API\Tax\TaxController;
use Illuminate\Support\Facades\Route;


Route::get('/paypal/success', [PaymentController::class, 'paypalSuccess'])->name('paypal.success');

Route::get('/system-setting', [SystemSettingController::class, 'index']);

Route::middleware(['guest'])->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('resend_otp', [RegisterController::class, 'resend_otp']);
    Route::post('register_verify_otp', [RegisterController::class, 'register_verify_otp']);
    Route::post('forgot-password', [RegisterController::class, 'forgot_password']);
    Route::post('verify-otp', [RegisterController::class, 'verify_otp']);
    Route::post('reset-password', [RegisterController::class, 'reset_password']);
    //valet register
    Route::post('register/valet', [RegisterController::class, 'valetRegister']);
    //Continue with google and facebook login
    Route::post('/social/login', [SocialLoginController::class, 'SocialLogin']);
});


Route::middleware('auth:sanctum')->group(function () {

    //logout
    Route::post('logout', [LoginController::class, 'logout']);

    //profile routes
    Route::get('user', [LoginController::class, 'user']);
    Route::get('/user-account-delete', [LoginController::class, 'deleteAccount']);
    Route::post('/user-delete', [LoginController::class, 'deleteUser']); // web link
    Route::get('/user-remaining-amount', [LoginController::class, 'userRemainingAmount']); //user account delete

    // Profile Update
    Route::post('/profile/update', [ProfileController::class, 'updateProfile']);

    // Update Password
    Route::post('/profile/update/password', [ProfileController::class, 'updatePassword']);

    // Add Payment
    Route::post('/add/payment', [ProfileController::class, 'addPayment']);

    // Add to Favourite
    Route::post('/favourite/add', [AddToFavouriteController::class, 'addToFavourite']);
    Route::get('/favourite/list', [AddToFavouriteController::class, 'getFavourite']);

    // shop Retrieve
    Route::get('/shop/fetch', [ShopListController::class, 'index']);
    Route::get('/shop/details/{id}', [ShopListController::class, 'shopDetails']);

    // Category Retrieve
    Route::get('/category/fetch', [CategoryListController::class, 'index']);

    // Shop Wise SubCategory Retrieve
    Route::get('/subcategory/fetch/{id}', [SubcategoryListController::class, 'index']);

    // Product Routes
    Route::get('/product/fetch/{id}', [ProductListController::class, 'index']);
    Route::get('/product/{shop_id}/{id}', [ProductListController::class, 'categoryProduct']);
    Route::get('/product/{shop_id}/{category_id}/{id}', [ProductListController::class, 'subCategoryProduct']);
    Route::get('/products/details/{id}', [ProductListController::class, 'details']);

    // Product Cart Controller
    Route::controller(CartController::class)->group(function () {
        Route::get('/products/cart/list/{id}', 'allCartItem');
        Route::post('/products/cart/add/{id}', 'addToCart');
        Route::post('/products/cart/plus/{id}', 'quantityPlus');
        Route::post('/products/cart/minus/{id}', 'quantityMinus');
        Route::post('/products/cart/remove/{id}', 'removeFromCart');
        Route::patch('/products/cart/note/{shop_id}/{id}/{product_id}', 'addNote');
    });

    // Create Orders
    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders/info/{shop_id}', 'orderInfo');
        Route::get('/orders/user', 'getUserOrders');
        Route::get('/orders/details/{id}', 'details');
        Route::get('/orders/all', 'userAllOrders');
        Route::get('/orders/valet/all/{shopId}', 'valetAllOrders');
        Route::get('/orders/valet/user/all', 'valetUserAllOrders');
        Route::get('/orders/valet/pending', 'valetPendingOrder');
        Route::get('/orders/accepted', 'acceptedOrders');
        Route::patch('/orders/product-found/{order_id}/{id}', 'productFound');
        Route::post('/orders/receipt/store', 'orderReceipt');
        Route::get('/orders/valet/check/{orderId}', 'valetProductCheck');
        Route::post('/orders/amount/check', 'remainingAmountCheck');
    });

    // Order Payment
    Route::get('/orders/complete/{id}',[PaymentController::class,'completeOrder']);
    Route::post('/create-order', [PaymentController::class, 'createOrder']);
    Route::post('/valet-shopping-payment/{orderId}', [PaymentController::class, 'payShopping']);
    Route::get('/orders/accept/{id}', [PaymentController::class, 'acceptOrder']);

    // Paypal Order Cancel
    Route::get('/order/cancel', function () {
        return response()->json(['message' => 'Payment canceled'], 400);
    })->name('order.cancel');

    // Tax Controller
    Route::controller(TaxController::class)->group(function () {
        Route::get('/tax/show', 'index');
    });

    // CMS Controller
    Route::controller(CmsController::class)->group(function () {
        Route::get('/cms/banner/list/{id}', 'index');
    });

    // Dynamic Page Controller
    Route::controller(DynamicPageController::class)->group(function () {
        Route::get('/dynamic/page/{id}', 'index');
    });

    // Notification Controller
    Route::controller(NotificationController::class)->group(function () {
        Route::get('/user/all/notification', 'index');
        Route::get('/user/unread/notification', 'unreadIndex');
    });

    // For valet profile
    Route::controller(ValetProfileController::class)->group(function () {
        Route::post('/valet/upload/image', 'index');
        Route::get('/valet/images/list', 'getProfileImages');
        Route::post('/valet/upload/paper-work', 'uploadPaperWork');
        Route::post('/valet/meet-requirement', 'meetRequirement');
        Route::get('/valet/requirement', 'requirement');
    });

});



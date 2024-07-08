<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductAdminController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Dashboard
Route::get('/dashboard', 'App\Http\Controllers\DashboardController@index');

// JWT Authentication
Route::get('/auth', 'App\Http\Controllers\UserController@getAuthenticatedUser');
Route::post('/register', 'App\Http\Controllers\UserController@register');
Route::post('/login', 'App\Http\Controllers\UserController@login');

// Address
Route::get('/user/default-address', 'App\Http\Controllers\UserAddressController@show');
Route::post('/user/create-user-address', 'App\Http\Controllers\UserAddressController@createUser');
Route::post('/user/address', 'App\Http\Controllers\UserAddressController@store');

// Product
Route::get('/products', 'App\Http\Controllers\ProductController@index');
Route::get('/products/{id}', 'App\Http\Controllers\ProductController@show');
Route::get('/product/hot-deal', 'App\Http\Controllers\ProductDealsController@hotDeals');
Route::post('/products', 'App\Http\Controllers\ProductController@store');
Route::delete('/products/{id}', 'App\Http\Controllers\ProductController@destroy');
// Tim kiem
Route::get('/search', 'App\Http\Controllers\ProductController@search');


// Product Orders
Route::post('/stripe', 'App\Http\Controllers\ProductOrdersController@stripePost');
Route::post('/product/orders', 'App\Http\Controllers\ProductOrdersController@store');

// Product Categories
Route::get('/product/categories', 'App\Http\Controllers\CategoryController@index');
Route::get('/product/categories/{id}/top-selling', 'App\Http\Controllers\CategoryController@topSelling');
Route::get('/product/categories/{id}/new', 'App\Http\Controllers\CategoryController@new');
Route::post('/product/categories', 'App\Http\Controllers\CategoryController@store');
Route::post('/product/categories/{id}', 'App\Http\Controllers\CategoryController@update');
Route::delete('/product/categories/{id}', 'App\Http\Controllers\CategoryController@destroy');


// Product Shopping Cart
Route::get('/product/cart-list/count', 'App\Http\Controllers\ProductShoppingCartController@cartCount');
Route::get('/product/cart-list/', 'App\Http\Controllers\ProductShoppingCartController@index');
Route::post('/product/cart-list', 'App\Http\Controllers\ProductShoppingCartController@store');
Route::post('/product/cart-list/guest', 'App\Http\Controllers\ProductShoppingCartController@guestCart');
Route::put('/product/cart-list/{id}', 'App\Http\Controllers\ProductShoppingCartController@update');
Route::delete('/product/cart-list/{id}', 'App\Http\Controllers\ProductShoppingCartController@destroy');

// Product Wishlist
Route::get('/product/wishlist/count', 'App\Http\Controllers\ProductWishlistController@count');
Route::get('/product/wishlist', 'App\Http\Controllers\ProductWishlistController@index');
Route::post('/product/wishlist', 'App\Http\Controllers\ProductWishlistController@store');
Route::delete('/product/wishlist/{id}', 'App\Http\Controllers\ProductWishlistController@destroy');

// Product Stocks
Route::get('/product/stocks/{id}', 'App\Http\Controllers\StockController@show');

// Newsletter
Route::post('/newsletter', 'App\Http\Controllers\NewsLetterController@store');
// Route::get('/newsletter/send', 'App\Http\Controllers\NewsLetterController@send');
Route::post('/subscribe', 'App\Http\Controllers\NewsLetterController@subscribe');
Route::post('/unsubscribe', 'App\Http\Controllers\NewsLetterController@unsubscribe');


// Product Admin delete, update
Route::resource('product', ProductAdminController::class);

// ViVnpay
Route::post('/vnpay', 'App\Http\Controllers\PaymentController@online_checkout');
// Route::get('/vnpay-return', 'App\Http\Controllers\PaymentController@vnpay_return');


// Feedback
Route::get('/feedback', 'App\Http\Controllers\FeedbackController@show');
Route::post('/feedback', 'App\Http\Controllers\FeedbackController@store');

// Revieww
Route::get('/reviews', 'App\Http\Controllers\ReviewController@index');

Route::get('/reviews/{product_id}', 'App\Http\Controllers\ReviewController@show');
Route::post('/reviews', 'App\Http\Controllers\ReviewController@store');
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\SaleController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/users', [AuthController::class, 'index']);
    Route::put('/user/change', [AuthController::class, 'changeUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    //Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    //Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/seller/{seller_id}', [ProductController::class, 'sellerProduct']);
    Route::post('/product', [ProductController::class, 'store']);
    Route::put('/product', [ProductController::class, 'store']);
    //Posts
    Route::post('/posts', [PostController::class, 'index']);
    Route::put('/post/status', [PostController::class, 'approveStatus']);
    Route::get('/post/seller/{seller_id}', [PostController::class, 'sellerPost']);
    Route::put('/post', [PostController::class, 'store']);
    Route::post('/post', [PostController::class, 'store']);
    Route::get('/post/cart/{buyer_id}', [OrderController::class, 'cartBuyer']);
    Route::get('/post/product/{product_id}/buyer/{buyer_id}', [PostController::class, 'postProductInfo']);
    //Order
    Route::get('/order/delete-cart/{product_id}/buyer/{buyer_id}', [OrderController::class, 'deleteCart']);
    //Payment
    Route::get('/payments', [PaymentTypeController::class, 'index']);
    //Sale
    Route::get('/payment/checkout/{order_id}/payment_type/{payment_typeid}', [SaleController::class, 'addPayment']);
});
Route::get('/product-sales/{seller_id}', [OrderController::class, 'productSale']);
Route::post('/order/add-to-cart', [OrderController::class, 'addToCart']);
Route::get('/order/cart-count/{buyer_id}', [OrderController::class, 'cartCount']);
Route::get('/post/approved/{buyer_id}', [PostController::class, 'approvedPost']);

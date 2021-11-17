<?php

use App\Http\Controllers\Backend\BackendController;
use App\Http\Controllers\Backend\ProductCategoriesController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\TagController;
use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',         [FrontendController::class, 'index'])->name('frontend.index');
Route::get('/cart',     [FrontendController::class, 'cart'])->name('frontend.cart');
Route::get('/checkout', [FrontendController::class, 'checkout'])->name('frontend.checkout');
Route::get('/detail',   [FrontendController::class, 'detail'])->name('frontend.detail');
Route::get('/shop',     [FrontendController::class, 'shop'])->name('frontend.shop');



Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    // Guest Middleware
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/login',              [BackendController::class, 'login'])->name('login');
        Route::get('/forgot-password',    [BackendController::class, 'forgot_password'])->name('forgot_password');
    });

    // Roles Middleware
    Route::group(['middleware' => ['roles', 'role:admin|supervisor']], function () {
        Route::get('/',                 [BackendController::class, 'index'])->name('index_route');
        Route::get('/index',            [BackendController::class, 'index'])->name('index');

        //remove Image ProductCategories
        Route::post('product_categories/remove-image', [ProductCategoriesController::class, 'remove_image'])
            ->name('product_categories.remove_image');
        //remove Image Products
        Route::post('products/remove-image', [ProductController::class, 'remove_image'])
            ->name('products.remove_image');

        // Route Resource
        Route::resource('product_categories',   ProductCategoriesController::class);
        Route::resource('products',             ProductController::class);
        Route::resource('tags',                 TagController::class);
    });
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\TagController;
use App\Http\Controllers\Backend\BackendController;
use App\Http\Controllers\Backend\cityController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\CustomerAddressController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Backend\SupervisorController;
use App\Http\Controllers\Backend\ProductCouponController;
use App\Http\Controllers\Backend\ProductReviewController;
use App\Http\Controllers\Backend\ProductCategoriesController;
use App\Http\Controllers\Backend\StateController;

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
        //remove Image Customer
        Route::post('customers/remove-image', [CustomerController::class, 'remove_image'])
            ->name('customers.remove_image');
        //remove Image Supervisors
        Route::post('supervisors/remove-image', [SupervisorController::class, 'remove_image'])
            ->name('supervisors.remove_image');
        //Get Customer For Search
        Route::get('customers/get_customers', [CustomerController::class, 'get_customers'])
            ->name('customers.get_customers');
        //Get State For Customer
        Route::get('states/get_states', [StateController::class, 'get_states'])
            ->name('states.get_states');
        //Get Cities For Customer
        Route::get('cities/get_cities', [cityController::class, 'get_cities'])
            ->name('cities.get_cities');

        // Route Resource
        Route::resource('product_categories',   ProductCategoriesController::class);
        Route::resource('products',             ProductController::class);
        Route::resource('tags',                 TagController::class);
        Route::resource('product_coupons',      ProductCouponController::class);
        Route::resource('product_reviews',      ProductReviewController::class);
        Route::resource('customers',            CustomerController::class);
        Route::resource('supervisors',          SupervisorController::class);
        Route::resource('countries',            CountryController::class);
        Route::resource('states',               StateController::class);
        Route::resource('cities',               CityController::class);
        Route::resource('customer_addresses',   CustomerAddressController::class);
    });
});

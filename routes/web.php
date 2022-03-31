<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use Illuminate\Http\Request;

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

use App\Models\Product;

// pages
Route::get('/', '\App\Http\Controllers\HomeController@index')->name('home');
Route::get('404', '\App\Http\Controllers\HomeController@page')->name('404');
Route::get('about', '\App\Http\Controllers\HomeController@page')->name('about');
Route::get('all-about-juice-cleansing', '\App\Http\Controllers\HomeController@page')->name('all-about-juice-cleansing');
Route::get('faq', '\App\Http\Controllers\HomeController@page')->name('faq');
Route::get('current-jobs', '\App\Http\Controllers\HomeController@page')->name('current-jobs');
Route::get('terms-and-conditions', '\App\Http\Controllers\HomeController@page')->name('terms-and-conditions');
Route::get('contact', '\App\Http\Controllers\HomeController@page')->name('contact');
Route::get('community', '\App\Http\Controllers\HomeController@page')->name('community');
Route::get('juice-cleanse-home-delivery', '\App\Http\Controllers\HomeController@page')->name('juice-cleanse-home-delivery');
Route::get('extended-organic-cleanses', '\App\Http\Controllers\HomeController@page')->name('extended-organic-cleanses');
Route::get('about-our-juice', '\App\Http\Controllers\HomeController@page')->name('about-our-juice');
Route::get('clinical-nutritionist-consults', '\App\Http\Controllers\HomeController@page')->name('clinical-nutritionist-consults');
Route::get('pages/{url}', '\App\Http\Controllers\HomeController@page')->name('page');

// shop and products
Route::get('shop', '\App\Http\Controllers\ShopController@index')->name('shop');
Route::get('category/{category}', '\App\Http\Controllers\ShopController@index')->name('category');
Route::get('product/{product}', '\App\Http\Controllers\ShopController@product')->name('product');

// users
Route::resource('users', '\App\Http\Controllers\UsersController');

// locations
Route::resource('locations', '\App\Http\Controllers\LocationsController');

// articles
Route::get('journal', '\App\Http\Controllers\ArticlesController@index')->name('blog');
Route::get('journal/category/{category}', '\App\Http\Controllers\ArticlesController@index')->name('blog.category');
Route::get('journal/{article}', '\App\Http\Controllers\ArticlesController@article')->name('article');

// cart & checkout
Route::resource('cart', '\App\Http\Controllers\CartController');
Route::post('cart/location', '\App\Http\Controllers\CartController@location')->name('cart.location');
Route::resource('checkout', '\App\Http\Controllers\CheckoutController');

// profile pages
Route::group(['middleware' => 'auth'], function () {
    Route::resource('account/profile', '\App\Http\Controllers\ProfileController');
    Route::resource('account/orders', '\App\Http\Controllers\OrdersController');
    Route::resource('account/subscriptions', '\App\Http\Controllers\SubscriptionsController');
    Route::get('account/payment-method', '\App\Http\Controllers\UsersController@paymentMethod')->name('payment-method');
    Route::resource('account/password', '\App\Http\Controllers\PasswordController');
});

// account orders
Route::resource('orders', '\App\Http\Controllers\OrdersController');
Route::post('orders/{order}/review', '\App\Http\Controllers\OrdersController@review');

// stripe
Route::post('/stripe/payment-intent', '\App\Http\Controllers\StripeController@paymentIntent');
Route::post('/stripe/setup-intent', '\App\Http\Controllers\StripeController@setupIntent');
Route::post('/stripe/add-payment-method', '\App\Http\Controllers\StripeController@addPaymentMethod');
Route::post('/stripe/charge-user', '\App\Http\Controllers\StripeController@chargeUser');
Route::get('/stripe/afterpay', '\App\Http\Controllers\StripeController@afterpay');
//review
Route::get('nutritionist-consults-review','\App\Http\Controllers\ReviewsController@nutritionist');
Route::post('nutritionist-consults-review/submit','\App\Http\Controllers\ReviewsController@nutritionistSubmit');

// auth
require __DIR__.'/auth.php';

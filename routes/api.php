<?php

use Illuminate\Http\Request;

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
// Authorization Resource
Route::post('/login', 'AuthenticateController@authenticate')->name('login');
Route::get('/logout', 'AuthenticateController@logout')->name('logout');

Route::group(['middleware' => ['jwt.auth']], function () {
	// Product Resource
	Route::get('/products', 'ProductController@showAll')->name('get-products');

	// Coupon Resource
	Route::get('/coupons', 'CouponController@showAll')->name('get-coupons');

	// Order Resource
	Route::post('/orders', 'OrderController@addOrder')->name('post-order');
	Route::post('/orders/{id}/confirm/payment', 'OrderController@confirmPayment')->name('confirm-payment');

	// Shipment Resource
	Route::get('/shipments/{id}/status', 'ShipmentController@showStatus')->name('shipment-status');

	// Logistic Partner Resource
	Route::get('/logistic-partners', 'LogisticPartnerController@showAll')->name('get-logistic-partner');

	// Admin Only
	Route::group(['middleware' => 'is_role:admin'], function() {
		// Order Resource
		Route::get('/orders/{id}', 'OrderController@show')->name('show-order');
		Route::post('/orders/{id}/ship', 'OrderController@shipOrder')->name('ship-order');
		Route::post('/orders/{id}/cancel', 'OrderController@cancelOrder')->name('cancel-order');

		// Shipment Resource
		Route::put('/shipments/{id}/status/{status}', 'ShipmentController@changeStatus')->name('change-shipment-status')
			->where('status', 'packing|on the way|delivered');
	});
});


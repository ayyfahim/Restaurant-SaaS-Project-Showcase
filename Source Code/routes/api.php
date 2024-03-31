<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function () {
//     return $request->user();
// });

Route::middleware(['auth:api'])->group(function () {
    // Route::get('/user', function (Request $request) {
    //     return $request->user();
    // });
    Route::get('/user', 'Auth\Login\CustomerController@user');
});


Route::post('/customer-login', 'Auth\Login\CustomerController@login');
Route::post('/customer-register/firebase', 'Auth\Login\CustomerController@registerFirebase');
Route::post('/customer/send-otp-to-mail', 'Auth\Login\CustomerController@sendOtpUsingFirebase');
Route::post('/customer/verify-otp-mail', 'Auth\Login\CustomerController@verifyOtpEmail');
Route::post('customer-login/{provider}/callback', 'Auth\Login\CustomerController@handleCallback');
Route::post('/customer-update', 'Auth\Login\CustomerController@update');
Route::post('/customer-update-password', 'Auth\Login\CustomerController@updatePassword');
Route::post('/customer-logout', 'Auth\Login\CustomerController@logout');
Route::get('/customer-me', 'Auth\Login\CustomerController@me');


Route::post('customer/checkCustomerByPhone', 'Auth\Login\CustomerController@checkCustomerByPhone');


Route::get('/customer/login/{provider}/', 'Auth\Login\SocialController@redirectToProvider');
Route::get('/customer/login/{provider}/callback', 'Auth\Login\SocialController@handleProviderCallback');


Route::post('/login', 'Auth\Login\StoreAuthApiController@login');
Route::post('/login/waiter', 'Auth\Login\WaiterAuthApiController@login');
Route::post('/login/kitchen', 'Auth\Login\KitchenAuthApiController@login');
Route::post('order/rating', 'API\StoreController@orederRating');

Route::group(['middleware' => ['auth:api']], function () {
    // store
    Route::post('/store/view', 'API\StoreController@view');
    Route::post('/store/update', 'API\StoreController@update');
    // orders


    Route::post('store/orders/view', 'API\StoreController@orders_view');
    Route::post('store/order/view/details', 'API\StoreController@orders_view_details');
    Route::post('store/order/update/status', 'API\StoreController@update_order_status');

    // category
    Route::post('/store/category/add', 'API\CategoryController@create');
    Route::post('/store/category/view', 'API\CategoryController@fetch');
    Route::post('/store/category/update', 'API\CategoryController@update');

    // product



    Route::post('/store/product/create', 'API\ProductController@create');
    Route::post('store/product/view', 'API\ProductController@fetch');
    Route::post('store/product/update', 'API\ProductController@update');

    // save notification token

    Route::post('store/update/firebase/token', 'API\ServiceController@save_store_fcm_token');

    // Waiters

    Route::get('allwaiters', 'API\WaiterController@all_waiter')->name('all_waiters');
    Route::post('addwaiters', "API\WaiterController@add_waiter")->name('addwaiters');
    Route::post('editwaiters/{waiter}', "API\WaiterController@edit_waiter")->name('editwaiters');
    Route::post('delete/waiters/{waiter}', "API\WaiterController@delete_waiter")->name('deletewaiters');

    // Kitchens

    Route::get('/allkitchens', 'API\KitchenController@all_kitchen')->name('all_kitchens');
    Route::post('addkitchens', "API\KitchenController@add_kitchen")->name('addkitchens');
    Route::post('editkitchens/{kitchen}', "API\KitchenController@edit_kitchens")->name('editkitchens');
    Route::post('delete/kitchens/{kitchen}', "API\KitchenController@delete_kitchens")->name('deletekitchens');
});

Route::post('web/store/account/orders/table', 'WEBAPI\OrderController@fetchTableOrder');
Route::post('web/store/account/select_table', 'WEBAPI\OrderController@selectTable')->middleware('auth:customer');
Route::post('web/store/account/leave_table', 'WEBAPI\OrderController@leaveTable')->middleware('auth:customer');
Route::post('web/store/account/fetch_table', 'WEBAPI\OrderController@fetchTable')->middleware('auth:customer');

// Route::get('typeform/store/1', 'WEBAPI\OrderController@typeform');

Route::group(['middleware' => ['auth:customer']], function () {
    Route::post('web/check_coupon', 'WEBAPI\CouponController@check_coupon');
    Route::post('/web/store/create/order', 'WEBAPI\OrderController@create');
    Route::post('web/store/account/orders', 'WEBAPI\OrderController@fetch');

    // Checkout & Payment

    Route::post('/getPaymentMethods', 'CheckoutController@getPaymentMethods');
    Route::post('/initiatePayment', 'CheckoutController@initiatePayment');
    Route::post('/submitAdditionalDetails', 'CheckoutController@submitAdditionalDetails');
    Route::match(['get', 'post'], '/handleShopperRedirect', 'CheckoutController@handleShopperRedirect');
    Route::post('/web/store/create/payment', 'CheckoutController@createPayment');


    Route::post('/limonetikCreateOrderForCard', 'CheckoutController@limonetikCreateOrderForCard');
    Route::post('/limonetikCreatePayment', 'CheckoutController@limonetikCreatePayment');
    Route::post('/limonetikGetOrder', 'CheckoutController@limonetikGetOrder');
    Route::post('/limonetikChargeOrder', 'CheckoutController@limonetikChargeOrder');
    Route::post('/limonetikChargeOrderForCard', 'CheckoutController@limonetikChargeOrderForCard');
    Route::post('/delete-card', 'Auth\Login\CustomerController@deleteCard');

    Route::post('/web/allergens/add', 'WEBAPI\AllergenController@addAllergens');
    Route::post('/web/check_coupon', 'WEBAPI\CouponController@check_coupon');
});
Route::post('/web/store/waiter/call', 'WEBAPI\StoreController@calling_waiter');

Route::group(['middleware' => ['auth:waiterApi']], function () {
    Route::get('waiter-calls', 'API\WaiterController@waiter_calls')->name('waiter_calls');
    Route::get('waiter-shifts', 'API\WaiterController@waiter_shifts')->name('waiter_shifts');
});

Route::group(['middleware' => ['auth:kitchenApi']], function () {
    Route::get('main-kitchen-orders', 'API\KitchenController@mainKitchenOrders')->name('mainKitchenOrders');
    Route::get('kitchen-orders', 'API\KitchenController@kitchenOrders')->name('kitchenOrders');
    Route::get('all-kitchen-orders', 'API\KitchenController@allKitchenOrders')->name('allKitchenOrders');
    Route::post('kitchen/update_order_status_changables', 'API\KitchenController@update_order_status_changables')->name('update_order_status_changables');
    Route::post('kitchen/update_order_status/{order}', 'API\KitchenController@update_order_status')->name('update_order_status');
    Route::post('kitchen/update_table_status/{table}', 'API\KitchenController@update_table_status')->name('update_table_status');
});

Route::post('/web/store/fetch', 'WEBAPI\StoreController@fetch');
Route::post('/web/store/check', 'WEBAPI\StoreController@checkIfStoreAndTableExist');
Route::post('/web/store/translation/active', 'WEBAPI\StoreController@translation');
Route::post('/web/store/translations', 'WEBAPI\StoreController@all_translation');
// Route::post('/web/store/waiter_shifts', 'WEBAPI\StoreController@all_waiter_shifts');
Route::post('/web/store/waiter_shift/{waiter}', 'WEBAPI\StoreController@waiter_shift');
Route::post('/web/store/update/waiter_shifts', 'WEBAPI\StoreController@update_waiter_shifts');
Route::get('/web/fetch/allergens', 'WEBAPI\AllergenController@fetchAllAllergens');

// Deliverect
Route::get('channelStatus', 'API\DeliverectController@ChannelStatus');
Route::get('createOrder', 'API\DeliverectController@createOrder');
Route::get('createToken', 'API\DeliverectController@createToken');
Route::get('allergensAndTags/{id}', 'API\DeliverectController@getAllergensAndTags');
Route::get('product-sync/{id}', 'API\DeliverectController@syncProduct');
Route::post('channelStatus/{id}', 'API\DeliverectController@updateUpdateChannelStatus');
Route::post('orderRating/{id}', 'API\DeliverectController@updateOrderRating');



// webhooks
Route::post('channelStatus', 'API\DeliverectController@ChannelStatus');
Route::post('orderStatusUpdate', 'API\DeliverectController@updateOrder');
Route::post('menuUpdate', 'API\DeliverectController@updateMenu');
Route::post('snoozeUnsnooze', 'API\DeliverectController@updateSnooze');
Route::post('busyMode', 'API\DeliverectController@updateBusyMode');
// Route::group(['middleware' => ['cors', 'json.response']], function () {

// });

// Store
// Route::get('store/{view_id}/{table_id?}', 'MobileApi\StoreController@getStore');

// Waiter
Route::post('waiter/login', 'Auth\Login\WaiterAuthApiController@login');
Route::get('waiter/me', 'Auth\Login\WaiterAuthApiController@me');
Route::get('waiter/refresh', 'Auth\Login\WaiterAuthApiController@refresh');
Route::get('waiter/logout', 'Auth\Login\WaiterAuthApiController@logout');
Route::get('waiter/waiter-calls', 'MobileApi\WaiterController@getWaiterCalls');
Route::get('waiter/waiter-shifts', 'MobileApi\WaiterController@getWaiterShifts');
Route::post('waiter/order/create', 'MobileApi\WaiterController@createOrder');

// Kitchen
Route::post('kitchen/login', 'Auth\Login\KitchenAuthApiController@login');
Route::get('kitchen/me', 'Auth\Login\KitchenAuthApiController@me');
Route::get('kitchen/refresh', 'Auth\Login\KitchenAuthApiController@refresh');
Route::get('kitchen/logout', 'Auth\Login\KitchenAuthApiController@logout');
Route::get('kitchen/dashboard/{store_id}/{kitchen_id}', 'MobileApi\KitchenController@dashboard');
Route::get('store/{view_id}/{table_id?}', 'MobileApi\StoreController@getStore');

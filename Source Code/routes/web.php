<?php

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
// Main screen
Route::get('/store/register', "Home\StoreHomeController@register")->name('store_register');
Route::post('/store/register', "Home\StoreHomeController@RegisterNewStore")->name('register_new_store');
Route::get('/store/register/download', "Home\StoreHomeController@downloadFile")->name('register_file_download');
Route::post('/store/register/upload', "Home\StoreHomeController@uploadFiles")->name('register_file_upload');
Route::get('/store/verify/{token}', "Home\StoreHomeController@verifyMail")->name('verify_mail');

Route::get('payment/successfull', 'CheckoutController@paymentSuccessfull')->name('paymentSuccessfull');


Route::get('/store/pricing', "Home\StoreHomeController@pricing")->name('store_pricing');
Route::get('/store/privacy', "Home\StoreHomeController@privacy")->name('privacy');


Route::get('/', "Home\StoreHomeController@home")->name('home');
Route::get('/start', "Home\StoreHomeController@start")->name('start');

Route::post('/change/language', "TranslationService@language_switcher")->name('change_language');


Route::get('/store/home', "Home\StoreHomeController@storeGetStarted");
Route::get('/store/{view_id}/{table_id?}', "Home\StoreHomeController@index")->name('view_store')->where('table_id', '[0-9]+');
Route::get('/store_cart/{all}', "Home\StoreHomeController@index")->where('all', '.*');
Route::get('/store/{view_id}/product/details/{product_id}', "Home\StoreHomeController@index")->where('all', '.*');
Route::get('/store/{view_id}/combo/details/{product_id}', "Home\StoreHomeController@index")->where('all', '.*');
Route::get('/store/{view_id}/category/details/{product_id}', "Home\StoreHomeController@index")->where('all', '.*');
Route::get('/store/view/qr/{view_id}/print', 'Home\QrController@print')->name('download_qr');
Route::get('/store/view/tblqr/{view_id}/{table_no}/print', 'Home\QrController@tblprint')->name('download_tblqr');


// admin side
Route::get('/admin/dashboard', 'AdminPageController@dashboard')->name('dashboard');
Route::get('/admin/dashboard/store/add', 'AdminPageController@add_store')->name('add_store');
Route::get('/admin/dashboard/store/all', 'AdminPageController@all_stores')->name('all_stores');
Route::get('/admin/dashboard/store/{id}/edit', 'AdminPageController@edit_stores')->name('edit_stores');
Route::post('/admin/dashboard/store/create', 'Admin\StoreController@create')->name('create_store');
Route::post('/admin/dashboard/store/{id}/update', "Admin\StoreController@update")->name('update_store');
Route::get('/admin/dashboard/sliders', 'AdminPageController@all_slider')->name('all_sliders');
Route::get('/admin/dashboard/slider/add', 'AdminPageController@add_slider')->name('add_slider');
Route::get('/admin/dashboard/slider/{id}/update', 'AdminPageController@update_slider')->name('update_slider');
Route::post('/admin/dashboard/slider/add', 'Admin\SliderController@add_slider')->name('upload_slider');
Route::patch('/admin/dashboard/slider/{id}/update', 'Admin\SliderController@update_slider')->name('edit_slider');
Route::delete('/admin/dashboard/slider/delete', 'Admin\SliderController@delete_slider')->name('delete_slider');
Route::get('/admin/dashboard/store/{store}/view-bank-details', 'AdminPageController@view_bank_details')->name('view_bank_details');
Route::resource('roles', Admin\RoleController::class);
Route::resource('permissions', Admin\PermissionController::class);

Route::middleware(['permission:view_users'])->group(function () {
    Route::resource('user', Admin\UserController::class);
    Route::get('user/changePassword/{user_id}', 'Admin\UserController@changePassword')->name('user.change-password-get');
    Route::put('user/changePassword/{user_id}', 'Admin\UserController@updatePassword')->name('user.change-password');
});






Route::get('/admin/dashboard/allergen/all', 'AdminPageController@all_allergens')->name('all_allergens');
Route::get('/admin/dashboard/allergen/add', 'AdminPageController@add_allergens')->name('add_allergens');
Route::get('/admin/dashboard/allergen/sync', 'Admin\AllergenController@sync_allergens')->name('sync_allergens');
Route::post('/admin/dashboard/allergen/add', 'Admin\AllergenController@create_allergen')->name('create_allergen');
Route::get('/admin/dashboard/allergen/{allergen}/edit', 'AdminPageController@edit_allergen')->name('edit_allergen');
Route::post('/admin/dashboard/allergen/{allergen}/update', "Admin\AllergenController@update_allergen")->name('update_allergen');
Route::delete('/admin/dashboard/allergen/{allergen}/delete', "Admin\AllergenController@delete_allergen")->name('delete_allergen');











Route::get('/admin/dashboard/settings', 'AdminPageController@settings')->name('settings');
Route::post('/admin/dashboard/settings', 'Admin\ApplicationController@update_account')->name('update_settings');
Route::post('/admin/dashboard/payment/settings/update', 'Admin\ApplicationController@update_payment_settings')->name('update_payment_settings');

Route::get('/admin/dashboard/settings/account', 'AdminPageController@account_settings')->name('account_settings');

Route::get('/admin/dashboard/settings/payment', 'AdminPageController@paymentsettings')->name('paymentsettings');
Route::post('/admin/dashboard/settings/payment', 'Admin\ApplicationController@update_account_settings')->name('update_account_settings');

Route::get('/admin/dashboard/settings/privacy', 'AdminPageController@privacy_policy')->name('privacy_policy');
Route::post('/admin/dashboard/settings/privacy/update', 'Admin\ApplicationController@update_privacy_policy')->name('update_privacy_policy');

Route::get('/admin/dashboard/settings/registration', 'AdminPageController@registration_policy')->name('registration_policy');
Route::post('/admin/dashboard/settings/registration/update', 'Admin\ApplicationController@update_registration_policy')->name('update_registration_policy');

Route::get('/admin/dashboard/settings/whatsapp', 'AdminPageController@whatsapp')->name('whatsapp');
Route::post('/admin/dashboard/whatsapp/settings/update', 'Admin\ApplicationController@update_whatsapp')->name('update_whatsapp');



// Database Migration:
Route::get('/admin/dashboard/settings/cache', 'AdminPageController@cache_settings')->name('cache_settings');

Route::get('/migrate', 'CacheController@migrate')->name('clear_app');

// Config Cache
Route::get('/config-cache', 'CacheController@configCache')->name('config_cache');

// application Cache
Route::get('/clear-cache', 'CacheController@clearCache')->name('app_cache');

// view Cache
Route::get('/view-cache', 'CacheController@viewCache')->name('view_cache');

Route::get('/newvalue', 'CacheController@newValue')->name('newvalue');

Route::get('/insertdata', 'CacheController@insertData')->name('insertdata');

Route::get('/privacynew', 'CacheController@privacyNew')->name('privacynew');


//subscription

Route::get('/admin/dashboard/subscription/all', 'AdminPageController@subscription')->name('all_subscription');
Route::get('/admin/dashboard/subscription/add', 'AdminPageController@addsubscription')->name('add_subscription');
Route::get('/admin/dashboard/subscription/{id}/edit', 'AdminPageController@editsubscription')->name('edit_subscription');
Route::patch('/admin/dashboard/subscription/{id}/edit', 'Admin\SubscriptionController@editsubscription')->name('update_subscription');

Route::post('/admin/dashboard/subscription/add', 'Admin\SubscriptionController@add_subscription')->name('add_new_subscription');


//translations
Route::get('/admin/dashboard/translations/all', 'AdminPageController@translations')->name('translations');

Route::get('/admin/dashboard/translations/add', 'AdminPageController@add_translations')->name('add_translations');
Route::post('/admin/dashboard/translations/add', 'Admin\TranslationController@add_translation')->name('add_translation');

Route::get('/admin/dashboard/translations/update/{id}', 'AdminPageController@update_translation')->name('update_translation_get');

Route::patch('/admin/dashboard/translations/update/{id}', 'Admin\TranslationController@update_translation')->name('update_translation');


//Route::get('/store/{view_id}', "Home\StoreHomeController@index")->name('view_store');
Route::any('/account/{all}/', "Home\UserController@index")->where('all', '.*');
Route::any('/checkout/{all}/', "CheckoutController@index")->where('all', '.*')->name('checkout');








Route::get('/restaurants/addproducts', 'RestaurantAdminPageController@restaurantsAddProducts');

Route::get('/restaurants/orders', 'RestaurantAdminPageController@restaurantsOrders');

Route::get('/restaurants/vieworder', 'RestaurantAdminPageController@restaurantsViewOrder');

Route::get('login_methods', 'Home\StoreHomeController@allLoginMethods')->name('all.logins');

Route::prefix('store/auth')
    ->as('store.')
    ->group(function () {
        Route::namespace('Auth\Login')
            ->group(function () {
                Route::get('login', 'StoreController@showLoginForm')->name('login');
                Route::post('login', 'StoreController@login')->name('login_post');
                Route::post('logout', 'StoreController@logout')->name('logout');
            });
    });

Route::prefix('/admin/store/')->as('store_admin.')
    ->group(function () {
        Route::get('dashboard', "RestaurantAdminPageController@index")->name('dashboard');


        Route::get('orders', "RestaurantAdminPageController@orders")->name('orders');
        Route::get('new_orders', "RestaurantAdminPageController@new_orders")->name('new_orders');
        Route::get('new_waiter_calls', "RestaurantAdminPageController@new_waiter_calls")->name('new_waiter_calls');

        Route::get('orders/details/{id}', "RestaurantAdminPageController@view_order")->name('view_order');
        Route::get('orders/status', "RestaurantAdminPageController@orderstatus")->name('orderstatus');


        Route::patch('orders/status/{id}/update', "StoreAdmin\UpdateOrderStatusController@updateStatus")->name("update_order_status");

        Route::get('categories', "RestaurantAdminPageController@categories")->name('categories');
        Route::get('addcategories', "RestaurantAdminPageController@addcategories")->name('addcategories');
        Route::get('editcategories/{id}/update', 'RestaurantAdminPageController@update_category')->name('update_category');

        Route::get('products', "RestaurantAdminPageController@products")->name('products');
        Route::get('addproducts', "RestaurantAdminPageController@addproducts")->name('addproducts');
        Route::get('editproducts/{id}/update', 'RestaurantAdminPageController@update_products')->name('update_products');

        Route::get('setmenus', "RestaurantAdminPageController@setmenus")->name('setmenus');
        Route::get('addsetmenu', "RestaurantAdminPageController@addsetmenu")->name('addsetmenu');
        Route::get('editsetmenu/{id}/update', 'RestaurantAdminPageController@update_setmenu')->name('update_setmenu');

        //        Route::get('userChangeStatus', 'RestaurantAdminPageController@userChangeStatus');

        Route::post('addcategories', 'StoreAdmin\CategoryController@add_category')->name('addcategories_post');
        Route::patch('editcategories/{id}/update', 'StoreAdmin\CategoryController@update_category')->name('edit_category');
        Route::post('addproducts', 'StoreAdmin\ProductController@addproducts')->name('addproducts_post');
        Route::patch('editproducts/{id}/update', 'StoreAdmin\ProductController@edit_products')->name('edit_products');
        Route::delete('products/delete', 'StoreAdmin\ProductController@delete_product')->name('delete_product');
        Route::delete('categories/delete', 'StoreAdmin\CategoryController@delete_category')->name('delete_category');
        Route::post('addsetmenu', 'StoreAdmin\SetmenuController@addsetmenu')->name('addsetmenu_post');
        Route::patch('editsetmenu/{id}/update', 'StoreAdmin\SetmenuController@edit_setmenu')->name('edit_setmenu');
        Route::delete('setmenu/delete', 'StoreAdmin\SetmenuController@delete_setmenu')->name('delete_setmenu');


        // Addon Categories
        Route::get('addon_categories', "RestaurantAdminPageController@addon_categories")->name('addon_categories');
        Route::get('addon_categories/{id}/update', 'RestaurantAdminPageController@addon_categories_edit')->name('addon_categories_edit');
        Route::patch('addon_categories/{id}/update', 'StoreAdmin\AddoncategoryController@update_addoncategory')->name('addon_categories_update');
        Route::post('addaddoncategories', 'StoreAdmin\AddoncategoryController@add_addoncategory')->name('addaddoncategories');

        // Route::get('addon', "RestaurantAdminPageController@addon")->name('addon');
        Route::post('addaddon', 'StoreAdmin\AddoncategoryController@add_addon')->name('add_addon');

        Route::get('update/addon/{id}', 'RestaurantAdminPageController@update_addon')->name('update_addon');
        Route::patch('update/addon/{id}', 'StoreAdmin\AddoncategoryController@update_addon')->name('update_addon_post');


        Route::delete('addon/delete', 'StoreAdmin\AddoncategoryController@delete_addon')->name('delete_addon');
        Route::delete('addon_categories/delete', 'StoreAdmin\AddoncategoryController@delete_addoncategories')->name('delete_addoncategories');

        Route::get('food_menues', "RestaurantAdminPageController@menues")->name('menues');
        Route::get('editmenues/{id}/update', 'RestaurantAdminPageController@update_menues')->name('update_menues');
        Route::post('addmenues', 'StoreAdmin\MenuController@add_menues')->name('add_menues');
        Route::patch('editmenues/{id}/update', 'StoreAdmin\MenuController@update_menues')->name('edit_menues');
        Route::delete('deletemenue', 'StoreAdmin\MenuController@delete_menu')->name('delete_menu');


        Route::get('/alltables', 'RestaurantAdminPageController@tables')->name('all_tables');
        Route::get('/all/table/report', 'RestaurantAdminPageController@table_report')->name('table_report');

        Route::get('addnewtable', 'RestaurantAdminPageController@add_table')->name('add_tables');
        Route::post('addnewtable', 'StoreAdmin\TableController@add_table')->name('add_new_table');
        Route::get('alltables/{id}/edit', 'RestaurantAdminPageController@edit_table')->name('edit_table');
        Route::patch('alltables/{id}/edit', 'StoreAdmin\TableController@edit_table')->name('edit_table_post');
        Route::get('/banner', 'RestaurantAdminPageController@banner')->name('banner');
        Route::get('addbanner', "RestaurantAdminPageController@addbanner")->name('addbanner');
        Route::post('addbanner', "StoreAdmin\StoreSliderController@add_slider")->name('add_banner');
        Route::get('/banner/{id}/edit', 'RestaurantAdminPageController@banneredit')->name('banneredit');
        Route::patch('banner/{id}/edit', 'StoreAdmin\StoreSliderController@update_slider')->name('update_slider');
        Route::delete('banner/delete', 'StoreAdmin\StoreSliderController@delete_slider')->name('delete_slider');
        Route::get('/subscription/plans', 'RestaurantAdminPageController@subscription_plans')->name('subscription_plans');
        Route::get('/subscription/plans/history', 'RestaurantAdminPageController@subscription_history')->name('subscription_history');
        Route::post('/subscription/compete/stripe/payment', 'StoreAdmin\CheckoutController@completeSubscriptionPayment')->name('subscription_complete_payment');
        Route::post('/subscription/compete/razorpay/payment', 'StoreAdmin\CheckoutController@completeRozorpaySubscriptionPayment')->name('subscription_razorpay_complete_payment');
        Route::get('/subscription/compete/payment/complete', 'StoreAdmin\CheckoutController@completeSubscriptionAfterPayment')->name('subscription_after_complete_payment');
        Route::get('/settings', 'RestaurantAdminPageController@settings')->name('settings');
        Route::get('/settings/add-bank-details', 'RestaurantAdminPageController@add_bank_details')->name('add_bank_details');
        Route::get('/settings/add-open-hours', 'RestaurantAdminPageController@add_open_hours')->name('add_open_hours');
        Route::get('/settings/deliverect', 'RestaurantAdminPageController@add_deliverect')->name('add_deliverect');
        Route::get('/settings/add-store-location', 'RestaurantAdminPageController@add_store_location')->name('add_store_location');
        Route::post('/settings/update', 'StoreAdmin\AccountSettings@update_store_settings')->name('update_store_settings');
        Route::post('/settings/update_bank_details', 'StoreAdmin\AccountSettings@update_bank_details')->name('update_bank_details');
        Route::post('/settings/update_open_hours', 'StoreAdmin\AccountSettings@update_open_hours')->name('update_open_hours');
        Route::post('/settings/update_deliverect_details', 'StoreAdmin\AccountSettings@update_deliverect_details')->name('update_deliverect_details');
        Route::post('/settings/update_store_location', 'StoreAdmin\AccountSettings@update_store_location')->name('update_store_location');
        // customers
        Route::get('/analytics', 'RestaurantAdminPageController@analytics')->name('customers');
        Route::get('/waiter/calls', 'RestaurantAdminPageController@waiter_calls')->name('waiter_calls');
        Route::patch('/waiter/call/update/{id}', 'StoreAdmin\WaiterController@update_waiter_call_status')->name('update_waiter_call_status');

        // Route::get('/allwaitershifts', 'RestaurantAdminPageController@allwaitershifts')->name('all_waiter_shifts');
        Route::get('/allwaiters', 'RestaurantAdminPageController@waiters')->name('all_waiters');
        Route::get('addwaiters', "RestaurantAdminPageController@addwaiters")->name('addwaiters');
        Route::post('addwaiters', "StoreAdmin\WaiterController@add_waiter")->name('addwaiters_post');
        Route::get('editwaiters/{waiter}', "RestaurantAdminPageController@editwaiters")->name('editwaiters');
        Route::patch('editwaiters/{waiter}', "StoreAdmin\WaiterController@edit_waiter")->name('editwaiters_post');
        Route::delete('delete/waiters/{waiter}', "StoreAdmin\WaiterController@delete_waiter")->name('deletewaiters');

        Route::post('set-waiter-to-table', "StoreAdmin\WaiterController@set_water_to_table")->name('set_water_to_table');

        Route::get('/allkitchens', 'RestaurantAdminPageController@kitchens')->name('all_kitchens');
        Route::get('addkitchens', "RestaurantAdminPageController@addkitchens")->name('addkitchens');
        Route::post('addkitchens', "StoreAdmin\KitchenController@add_kitchen")->name('addkitchens_post');
        Route::get('editkitchens/{kitchen}', "RestaurantAdminPageController@editkitchens")->name('editkitchens');
        Route::patch('editkitchens/{kitchen}', "StoreAdmin\KitchenController@edit_kitchens")->name('editkitchens_post');
        Route::delete('delete/kitchens/{kitchen}', "StoreAdmin\KitchenController@delete_kitchens")->name('deletekitchens');
        Route::get('editkitchens/{kitchen}/change-password', "RestaurantAdminPageController@changePassword")->name('changePassword');
        Route::patch('editkitchens/{kitchen}/change-password', "StoreAdmin\KitchenController@update_password")->name('update_password');

        Route::get('addkitchenlocation', "RestaurantAdminPageController@addkitchenlocation")->name('addkitchenlocation');
        Route::post('addkitchenlocation', "StoreAdmin\KitchenLocationController@addkitchenlocation")->name('addkitchenlocation_post');

        Route::get('addtimerestrictions', "RestaurantAdminPageController@addtimerestrictions")->name('timerestrictions');
        Route::post('addtimerestrictions', "StoreAdmin\TimeRestrictionController@addtimerestrictions")->name('addtimerestrictions');
        Route::get('edittimerestrictions/{id}', "RestaurantAdminPageController@edittimerestrictions")->name('edittimerestrictions');
        Route::patch('edittimerestrictions/{id}', "StoreAdmin\TimeRestrictionController@edittimerestrictions")->name('edittimerestrictions_post');
        Route::delete('deletetimerestrictions/{id}', "StoreAdmin\TimeRestrictionController@deletetimerestrictions")->name('deletetimerestrictions');

        Route::get('discount', "RestaurantAdminPageController@discount")->name('discount');
        Route::get('adddiscount', "RestaurantAdminPageController@adddiscount")->name('adddiscount');
        Route::post('adddiscount', "StoreAdmin\DiscountController@adddiscount")->name('adddiscount_post');
        Route::get('editdiscount/{id}', "RestaurantAdminPageController@editdiscount")->name('editdiscount');
        Route::patch('updatediscount/{id}', "StoreAdmin\DiscountController@updatediscount")->name('updatediscount');
        Route::delete('deletediscount/{discount}', "StoreAdmin\DiscountController@deletediscount")->name('deletediscount');

        Route::get('coupon', "RestaurantAdminPageController@coupon")->name('coupon');
        Route::get('addcoupon', "RestaurantAdminPageController@addcoupon")->name('addcoupon');
        Route::post('addcoupon', "StoreAdmin\CouponController@addcoupon")->name('addcoupon_post');
        Route::get('editcoupon/{coupon}', "RestaurantAdminPageController@editcoupon")->name('editcoupon');
        Route::patch('updatecoupon/{coupon}', "StoreAdmin\CouponController@updatecoupon")->name('updatecoupon');
        Route::delete('deletecoupon/{coupon}', "StoreAdmin\CouponController@deletecoupon")->name('deletecoupon');
    });

Route::prefix('waiter/auth')
    ->as('waiter.')
    ->group(function () {
        Route::namespace('Auth\Login')
            ->group(function () {
                Route::get('login', 'WaiterController@showLoginForm')->name('login');
                Route::post('login', 'WaiterController@login')->name('login_post');
                Route::post('logout', 'WaiterController@logout')->name('logout');
            });
    });

Route::prefix('/admin/waiter/')->as('waiter_admin.')
    ->group(
        function () {
            Route::get('waiter-call', "WaiterAdminPageController@waiter_calls")->name('waiter_calls');
            Route::get('current-waiter-shifts', "WaiterAdminPageController@waiter_shifts")->name('waiter_shifts');
            Route::get('order-requests', "WaiterAdminPageController@order_requests")->name('order_requests');
            Route::patch('/call/update/{id}', 'WaiterAdmin\WaiterController@update_waiter_call_status')->name('update_waiter_call_status');
            Route::patch('/call/update/order/{id}', 'WaiterAdmin\WaiterController@update_waiter_call_status_order')->name('update_waiter_call_status_order');
            Route::patch('/call/update/order/table/{id}', 'WaiterAdmin\WaiterController@update_waiter_call_status_table')->name('update_waiter_call_status_table');

            Route::get('create-order', "WaiterAdminPageController@create_orders")->name('createOrder');
            Route::post('api/get-product', 'WaiterAdmin\WaiterController@getProductDetails')->name('waiter_get_product_details');
            Route::post('add-to-cart', 'WaiterAdmin\WaiterController@add_to_cart')->name('add_to_cart');
            Route::post('create/order', 'WaiterAdmin\WaiterController@create_order')->name('create_order');
            Route::post('api/get-customer-details', 'WaiterAdmin\WaiterController@getCustomerDetails')->name('getCustomerDetails');
            Route::post('api/fetch-table-order-users', 'WaiterAdmin\WaiterController@fetchTableOrderUsers')->name('fetchTableOrderUsers');
        }
    );

Route::prefix('kitchen/auth')
    ->as('kitchen.')
    ->group(function () {
        Route::namespace('Auth\Login')
            ->group(function () {
                Route::get('login', 'KitchenController@showLoginForm')->name('login');
                Route::post('login', 'KitchenController@login')->name('login_post');
                Route::post('logout', 'KitchenController@logout')->name('logout');
            });
    });

Route::prefix('/admin/kitchen/')->as('kitchen_admin.')
    ->group(
        function () {
            Route::get('dashboard', "KitchenAdminPageController@dashboard")->name('dashboard');
            Route::get('dashboard_kitchen', "KitchenAdminPageController@authKitchenLocation")->name('kitchenlocation');

            Route::patch('/order/update/{order}', 'KitchenAdmin\KitchenController@update_order_status')->name('update_order_status');
            Route::patch('table/{table}/orders/update', 'KitchenAdmin\KitchenController@update_table_status')->name('update_table_status');
            Route::post('/order/update/changables', 'KitchenAdmin\KitchenController@update_order_status_changables')->name('update_order_status_changables');
            // Route::get('order-requests', "WaiterAdminPageController@order_requests")->name('order_requests');
            // Route::patch('/call/update/{id}', 'WaiterAdmin\WaiterController@update_waiter_call_status')->name('update_waiter_call_status');
            // Route::patch('/call/update/order/{id}', 'WaiterAdmin\WaiterController@update_waiter_call_status_order')->name('update_waiter_call_status_order');
        }
    );


Auth::routes();
Route::get('/storejs/{view_id}', "Home\StoreHomeController@indexjs");

Route::get('/test', "Notification\NotificationController@FcmStoreNotification");

// Route::prefix('customer/auth')
//     ->as('customer.')
//     ->group(function () {
//         Route::namespace('Auth\Login')
//             ->group(function () {
//                 Route::get('login', 'CustomerController@showLoginForm')->name('login');
//                 Route::post('login', 'CustomerController@login')->name('login');
//                 Route::post('logout', 'CustomerController@logout')->name('logout');
//             });
//     });

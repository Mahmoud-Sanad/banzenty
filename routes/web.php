<?php

use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\DataDeletionController;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Route::get('popular/users', 'Admin\UsersController@popularUsers')->name('users.popular');
// Route::get('popular/stations', 'Admin\StationController@popularStations')->name('stations.popular');
// Route::get('usage/services', 'Admin\ServiceController@servicesUsage')->name('services.usage');

Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('charts', 'HomeController@charts')->name('charts');
    Route::get('fleet/charts', 'HomeController@fleetChart')->name('fleet.charts');
    // Statistics

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::post('fleet/removeuser', 'UsersController@removeUserFromFleet')->name('fleets.remove');
    Route::post('fleet/adduser', 'UsersController@addUserToFleet')->name('fleets.add');
    Route::get('users/fleets', 'UsersController@fleets')->name('users.fleets');
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/media', 'UsersController@storeMedia')->name('users.storeMedia');
    Route::post('users/ckmedia', 'UsersController@storeCKEditorImages')->name('users.storeCKEditorImages');
    Route::resource('users', 'UsersController');
    Route::get('car-owners/users', 'UsersController@carOwners')->name('users.car.owners');
    Route::get('station-admin/users', 'UsersController@stationAdmins')->name('users.station.admins');
    Route::get('employee/users', 'UsersController@employees')->name('users.employees');
    Route::get('fleet-owners/users', 'UsersController@myUsers')->name('fleet.users');

    //subscriptions
    Route::post('attach/{user}/{plan}', [SubscriptionController::class, 'attach'])->name('subscription.attach');
    Route::post('renew/{user}', [SubscriptionController::class, 'renew'])->name('subscription.renew');
    Route::post('cancel/{user}', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

    // Car
    Route::delete('subscription-requests/destroy', 'SubscriptionRequestController@massDestroy')->name('subscription-requests.massDestroy');
    Route::resource('subscription-requests', 'SubscriptionRequestController');

    // Station
    Route::delete('stations/destroy', 'StationController@massDestroy')->name('stations.massDestroy');
    Route::post('stations/parse-csv-import', 'StationController@parseCsvImport')->name('stations.parseCsvImport');
    Route::post('stations/process-csv-import', 'StationController@processCsvImport')->name('stations.processCsvImport');
    Route::resource('stations', 'StationController');

    // Fuel
    Route::delete('fuels/destroy', 'FuelController@massDestroy')->name('fuels.massDestroy');
    Route::resource('fuels', 'FuelController');

    // Service
    Route::delete('services/destroy', 'ServiceController@massDestroy')->name('services.massDestroy');
    Route::post('services/media', 'ServiceController@storeMedia')->name('services.storeMedia');
    Route::post('services/ckmedia', 'ServiceController@storeCKEditorImages')->name('services.storeCKEditorImages');
    Route::post('services/parse-csv-import', 'ServiceController@parseCsvImport')->name('services.parseCsvImport');
    Route::post('services/process-csv-import', 'ServiceController@processCsvImport')->name('services.processCsvImport');
    Route::resource('services', 'ServiceController');

    // Category
    Route::delete('categories/destroy', 'CategoryController@massDestroy')->name('categories.massDestroy');
    Route::resource('categories', 'CategoryController');

    // Plan
    Route::delete('plans/destroy', 'PlanController@massDestroy')->name('plans.massDestroy');
    Route::resource('plans', 'PlanController');

    // Contact Us
    Route::delete('contact-us/messages', 'ContactUsController@massDestroy')->name('contact.us.massDestroy');
    Route::get('contact-us/messages', 'ContactUsController@index')->name('contact.us.index');
    Route::get('contact-us/messages/{contact_us}', 'ContactUsController@show')->name('contact.us.show');
    Route::delete('contact-us/messages/{contact_us}', 'ContactUsController@destroy')->name('contact.us.destroy');

    // Banner
    Route::delete('banners/destroy', 'BannerController@massDestroy')->name('banners.massDestroy');
    Route::post('banners/media', 'BannerController@storeMedia')->name('banners.storeMedia');
    Route::post('banners/ckmedia', 'BannerController@storeCKEditorImages')->name('banners.storeCKEditorImages');
    Route::get('banners/target_ids', 'BannerController@getTargetIds')->name('banners.target-ids');
    Route::resource('banners', 'BannerController');

    // Notification
    Route::delete('notifications/destroy', 'NotificationController@massDestroy')->name('notifications.massDestroy');
    Route::resource('notifications', 'NotificationController');

    // Order

    Route::delete('orders/destroy', 'OrderController@massDestroy')->name('orders.massDestroy');
    Route::get('stations/{id}/services', 'OrderController@stationServices')->name('orders.services');
    Route::get('orders/user', 'OrderController@findUserByCarPlate')->name('orders.user');
    Route::get('orders/myRequests', 'OrderController@myRequests')->name('orders.my-orders');
    Route::view('orders/qr', 'admin.orders.qr-scanner')->name('orders.qr-scanner');
    Route::resource('orders', 'OrderController');

    // Car
    Route::delete('cars/destroy', 'CarController@massDestroy')->name('cars.massDestroy');
    Route::resource('cars', 'CarController');

    // Company
    Route::delete('companies/destroy', 'CompanyController@massDestroy')->name('companies.massDestroy');
    Route::post('companies/media', 'CompanyController@storeMedia')->name('companies.storeMedia');
    Route::post('companies/ckmedia', 'CompanyController@storeCKEditorImages')->name('companies.storeCKEditorImages');
    Route::post('companies/parse-csv-import', 'CompanyController@parseCsvImport')->name('companies.parseCsvImport');
    Route::post('companies/process-csv-import', 'CompanyController@processCsvImport')->name('companies.processCsvImport');
    Route::resource('companies', 'CompanyController');

    // Settings
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::delete('destroy', 'SettingsController@massDestroy')->name('massDestroy');
        Route::get('edit', 'SettingsController@edit')->name('edit');
        Route::post('update', 'SettingsController@update')->name('update');
        Route::get('terms/edit', 'SettingsController@editTermsAndConditions')->name('terms.edit');
        Route::post('terms/update', 'SettingsController@updateTermsAndConditions')->name('terms.update');
        Route::get('privacy/edit', 'SettingsController@editPrivacyPolicy')->name('privacy.edit');
        Route::post('privacy/update', 'SettingsController@updatePrivacyPolicy')->name('privacy.update');
    });


    // Reward
    Route::delete('rewards/destroy', 'RewardController@massDestroy')->name('rewards.massDestroy');
    Route::post('rewards/media', 'RewardController@storeMedia')->name('rewards.storeMedia');
    Route::post('rewards/ckmedia', 'RewardController@storeCKEditorImages')->name('rewards.storeCKEditorImages');
    Route::resource('rewards', 'RewardController');

    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});

Route::get('privacy-policy', function () {
    return view('privacy-policy', ['privacy' => Settings::getValue('privacy-policy')]);
});

Route::view('contact-us', 'contact-us');
Route::post('contact-us/send', 'Admin\HomeController@contactUs')->name('contact-us.send');
Route::view('thank-you', 'thank-you')->name('thank-you');

Route::view('delete-account', 'request-data-deletion');
Route::post('delete-account', [DataDeletionController::class, 'sendEmail'])->name('request-data-deletion');
Route::view('delete-account/confirm', 'confirm-data-deletion');
Route::post('delete-account/confirm', [DataDeletionController::class, 'confirm'])->name('confirm-data-deletion');
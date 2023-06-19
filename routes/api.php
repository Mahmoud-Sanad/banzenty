<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\RewardController;
use App\Http\Controllers\Api\StationController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'api.', 'namespace' => 'Api'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('register',             [AuthController::class, 'register']);
        Route::post('verify',               [AuthController::class, 'verify']);
        Route::post('resend-code',          [AuthController::class, 'resendVerifictaionCode']);
        Route::post('login',                [AuthController::class, 'login']);
        Route::post('logout',               [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::post('request-password-reset', [AuthController::class, 'requestPasswordReset']);
        Route::post('reset-password',       [AuthController::class, 'resetPassword']);
        Route::get('terms-and-conditions',  [AuthController::class, 'getTermsAndConditions']);
    });

    Route::group(['prefix' => 'profile', 'middleware' => 'auth:sanctum'], function () {
        Route::get('/',                         [UserController::class, 'profileDetails']);
        Route::post('update',                   [UserController::class, 'update']);
        Route::post('password/change',          [UserController::class, 'changePassword']);
        Route::get('notifications',             [UserController::class, 'notifications']);
        Route::get('unread-notifications-count',[UserController::class, 'unreadNotificationsCount']);
        Route::post('cars/add',                 [UserController::class, 'addCar']);
        Route::post('cars/delete',              [UserController::class, 'deleteCar']);
        Route::get('cars',                      [UserController::class, 'listCars']);
        Route::get('requests',                  [UserController::class, 'requests']);
    });

    Route::group(['prefix' => 'stations'], function () {
        Route::get('/',                     [StationController::class, 'index']);
        Route::get('/filters',              [StationController::class, 'getFilters']);
        Route::get('list',                  [StationController::class, 'list']);
        Route::get('/{id}',                 [StationController::class, 'details']);
    });

    Route::get('home', [HomeController::class, 'home']);

    Route::post('contact-us', [HomeController::class, 'contactUs']);

    Route::group(['prefix' => 'rewards', 'middleware' => 'auth:sanctum'], function () {
        Route::get('/',                     [RewardController::class, 'index']);
        Route::post('redeem',               [RewardController::class, 'redeem']);
    });

    Route::group(['prefix' => 'plans', 'middleware' => 'auth:sanctum'], function () {
        Route::get('/',                     [SubscriptionController::class, 'plans']);
        Route::post('subscribe',            [SubscriptionController::class, 'subscribe']);
        Route::get('my-subscription',       [SubscriptionController::class, 'mySubscription']);
        Route::post('renew',                [SubscriptionController::class, 'renew']);
        Route::post('cancel',               [SubscriptionController::class, 'cancel']);
    });
});

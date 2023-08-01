<?php

use App\Http\Controllers\Api\DriverCourseController;
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

Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
    Route::post('auth/login', 'AuthController@login');
    Route::get('auth/bothutesthoi/ahii', 'AuthController@testAI');
    Route::get('driver-course/export-shift','DriverCourseController@export_shift');
    Route::get('driver-course/export-shift-express-charge','DriverCourseController@export_shift_express_charge');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('user/save-token-fcm', 'UserController@saveTokenFCM');
        Route::get('calendar/index', 'CalendarController@index');
        Route::group(['middleware' => 'admin'], function () {
            Route::post('calendar/setup-data', 'CalendarController@store');
            Route::post('calendar/delete', 'CalendarController@destroy');
            Route::apiResource('user', 'UserController');
            Route::apiResource('driver', 'DriverController');
            Route::get('course/export', 'CourseController@export');
            Route::post('course/import', 'CourseController@import');
            Route::delete('course/delete-many', 'CourseController@deleteMultiple');
            Route::apiResource('course', 'CourseController');
            Route::apiResource('customer', 'CustomerController');
            Route::apiResource('cash-in', 'CashInController');
            Route::get('driver-course/total-extra-cost','DriverCourseController@total_extra_cost');
            Route::get('driver-course/get-all-express-charge','DriverCourseController@get_all_express_charge');
            Route::get('driver-course/total-express-charge-cost','DriverCourseController@total_express_charge_cost');
            Route::post('driver-course/update-course','DriverCourseController@update_course');
            Route::apiResource('driver-course', 'DriverCourseController');
            Route::apiResource('/driver/{driver}/cash-out', 'CashOutController');
        });
        Route::group(['prefix' => 'auth'], function () {
            Route::post('refresh', 'AuthController@refresh');
            Route::post('logout', 'AuthController@logout');
        });
    });
});

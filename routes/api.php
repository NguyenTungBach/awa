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
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('user/save-token-fcm', 'UserController@saveTokenFCM');
        Route::get('calendar/index', 'CalendarController@index');
        Route::group(['middleware' => 'admin'], function () {
            Route::post('calendar/setup-data', 'CalendarController@store');
            Route::post('calendar/delete', 'CalendarController@destroy');
            Route::apiResource('user', 'UserController');
            Route::apiResource('driver', 'DriverController');
            Route::post('course/export', 'CourseController@export');
            Route::get('course/delete-many', 'CourseController@deleteMany');
            Route::apiResource('course', 'CourseController');
            Route::apiResource('customer', 'CustomerController');
            Route::get('driver-course/total-extra-cost','DriverCourseController@total_extra_cost');
            Route::post('driver-course/update-course','DriverCourseController@update_course');
            Route::apiResource('driver-course', 'DriverCourseController');
        });
        Route::group(['prefix' => 'auth'], function () {
            Route::post('refresh', 'AuthController@refresh');
            Route::post('logout', 'AuthController@logout');
        });
    });
});

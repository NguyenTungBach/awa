<?php

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

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('user/save-token-fcm', 'UserController@saveTokenFCM');
        Route::get('calendar/index', 'CalendarController@index');
        Route::get('shift/export-to-excel', 'ShiftController@exportToExcel');
        Route::group(['middleware' => 'admin'], function () {
            Route::post('calendar/setup-data', 'CalendarController@store');
            Route::post('calendar/delete', 'CalendarController@destroy');
            Route::apiResource('user', 'UserController');
            Route::apiResource('driver', 'DriverController');
            Route::apiResource('course', 'CourseController');
            Route::apiResource('day-off', 'DayOffController');
            Route::get('course-schedule/export-data', 'CourseScheduleController@export');
            Route::post('course-schedule/updates', 'CourseScheduleController@updateMany');
            Route::post('course-schedule/import', 'CourseScheduleController@import');
            Route::apiResource('course-schedule', 'CourseScheduleController');
            Route::post('shift', 'ShiftController@store');
            Route::get('shift/detail-cell', 'ShiftController@detailCell');
            Route::post('shift/detail-cell', 'ShiftController@updateCell');
            Route::post('shift/edits', 'ShiftController@editAI');
        });
        Route::get('shift', 'ShiftController@index');
        Route::group(['prefix' => 'auth'], function () {
            Route::post('refresh', 'AuthController@refresh');
            Route::post('logout', 'AuthController@logout');
        });
    });
});

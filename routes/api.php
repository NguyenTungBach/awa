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
        Route::get('shift/grade-tab-to-excel', 'ShiftController@download');
        Route::group(['middleware' => 'admin'], function () {
            Route::post('calendar/setup-data', 'CalendarController@store');
            Route::post('calendar/delete', 'CalendarController@destroy');
            Route::apiResource('user', 'UserController');
            Route::apiResource('driver', 'DriverController');
            Route::apiResource('course', 'CourseController');
            Route::get('driver-course/list/{id}', 'DriverCourseController@index');
            Route::delete('driver-course/delete/{id}', 'DriverCourseController@destroy');
            Route::post('driver-course', 'DriverCourseController@store');
            Route::apiResource('course-pattern', 'CoursePatternController');
            Route::post('course-pattern/updates', 'CoursePatternController@updateMany');
            Route::apiResource('day-off', 'DayOffController');
            Route::get('course-schedule/export-data', 'CourseScheduleController@export');
            Route::post('course-schedule/updates', 'CourseScheduleController@updateMany');
            Route::post('course-schedule/import', 'CourseScheduleController@import');
            Route::apiResource('course-schedule', 'CourseScheduleController');
            Route::apiResource('report', 'ReportController');
//            Route::get('shift', 'ShiftController@index');
            Route::post('shift', 'ShiftController@store');
            Route::get('parctical-performance/export-to-excel', 'ReportController@exportToExcel');
            Route::get('parctical-performance/export-to-pdf', 'ReportController@exportToPdf');
            Route::get('shift/check-data-result', 'ShiftController@checkDataResult');
            Route::get('shift/detail-cell', 'ShiftController@detailCell');
            Route::post('shift/detail-cell', 'ShiftController@updateCell');
            Route::post('shift/edits', 'ShiftController@editAI');
            Route::get('shift/check-infomation-ai', 'ShiftController@checkInfomationAI');
            Route::get('shift/get-message-response-ai','ShiftController@getMessageAI');
            Route::apiResource('parctical-performance', 'ReportController');
        });
        Route::get('shift', 'ShiftController@index');
        Route::group(['prefix' => 'auth'], function () {
            Route::post('refresh', 'AuthController@refresh');
//            Route::get('user', 'UserController@user');
            Route::post('logout', 'AuthController@logout');
        });
    });
});

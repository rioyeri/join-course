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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('allteacher', 'HelperController@getAllTeacher')->name('getAllTeacher');

Route::get('getDatas', 'HelperController@apigetDatas')->name('apigetDatas');

// Route::middleware(['checkUser'])->group(function () {
// Route::group(['middleware' => 'checkUser'], function(){
    Route::get('package/{id}', 'PackageController@show');

    Route::get('orderreview/{id}', 'OrderReviewController@show');

    Route::get('orderhistory/{id}', 'OrderController@show');

    Route::get('getreview/{id}', 'HelperController@apiGetReview');

    Route::get('bestteacher', 'StatisticsController@bestTeacher');
    Route::get('mostsubject', 'StatisticsController@mostSubject');
    Route::get('gradestats', 'StatisticsController@GradeStats');
    Route::get('ordertypestats', 'StatisticsController@OrderTypeStats');
    Route::get('packagestats', 'StatisticsController@PackageStats');

    Route::get('orderreport', 'ReportController@OrderReport');
    Route::get('incomereport', 'ReportController@IncomeReport');
// });
<?php

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

Route::group(['prefix' => 'google', 'namespace' => 'Auth', 'as' => 'google.'], function(){
    Route::get('/', 'LoginController@redirect')->name('redirect');
    Route::get('callback', 'LoginController@callback')->name('callback');
});
// Route::get('/testuser','TestController@index');
Route::get('/','HomeController@index')->name('getHome');
// Route::get('/login','HomeController@index2')->name('getHome2');
Route::get('/login', 'HomeController@get_login')->name('get_login');
Route::post('login','HomeController@login')->name('Login');
Route::get('/register', 'HomeController@get_register')->name('get_register');
Route::post('register', 'HomeController@post_register')->name('post_register');

// User Helper
Route::get('checkUsernameAvailability','UserController@checkUsernameAvailability')->name('checkUsernameAvailability');
Route::get('password/{id}/create', 'UserController@createPassword')->name('createPassword');
Route::post('password/{id}/store', 'UserController@storePassword')->name('storePassword');
Route::post('forgotpassword', 'UserController@forgotPassword')->name('forgotpassword');
Route::get('resetpassword/{email}/{token}', 'UserController@forgetPassword');
Route::post('resetpassword/{email}/{token}', 'UserController@resetPassword')->name('resetPassword');

// maintenance
Route::get('maintenance', 'HomeController@maintenance')->name('maintenance');

// Template
Route::get('pilihtemplate', 'TemplateController@index');

Route::get('searchTeacherOrSubject', 'HelperController@searchTeacherOrSubject')->name('searchTeacherOrSubject');

Route::middleware(['checkUser'])->group(function () {
    // Profil for Footer
    Route::get('/profile','ProfileController@index')->name('profile.index');
    Route::get('/profile/{id}/edit','ProfileController@edit')->name('profile.edit');
    Route::put('/profile/{id}/update','ProfileController@update')->name('profile.update');
    Route::get('/profile/{id}/show','ProfileController@show')->name('profile.show');
    Route::delete('/profile/{id}','ProfileController@delete')->name('profile.destroy');

    // Resources
    Route::resources([
        // Employee
        'user' => 'UserController',
        // Modul Management
        'modul' => 'ModulController',
        // Sub Modul Management
        'submodul' => 'SubModulController',
        // Role Management
        'role' => 'RoleController',
        // Role Mapping
        'rolemapping' => 'RoleMappingController',
        // Menu Mapping
        'menumapping' => 'MenuController',
        // Course
        'course' => 'CourseController',
        // Course Payment
        'coursepayment' => 'CoursePaymentController',
        // Student
        'student' => 'StudentController',
        // Teacher
        'teacher' => 'TeacherController',
        // Order
        'order' => 'OrderController',
        // Package
        'package' => 'PackageController',
    ]);

    Route::get('logout','HomeController@logout')->name('Logout');

    // ------------------------ HELPER -------------------------------------------------
    Route::get('/datakota','HelperController@getDataKota')->name('getDataKota');
    Route::get('/datacoa','HelperController@ajxCoa')->name('ajxCoa');
    Route::get('getCheckBeforeDelete', 'HelperController@checkBeforeDelete')->name('checkBeforeDelete');
    Route::get('/getTeacherFee', 'HelperController@getTeacherFee')->name('getTeacherFee');
    Route::get('/getData','HelperController@getData')->name('getData');

    // Course Helper
    Route::post('/course/{id}/changestatus', 'CourseController@changeStatus')->name('changeStatusCourse');

    // Package Helper
    Route::post('/package/{id}/changestatus', 'PackageController@changeStatus')->name('changeStatusPackage');

    // Teacher Helper
    Route::post('/teacher/{id}/changestatus', 'TeacherController@changeStatus')->name('changeStatusTeacher');
    Route::get('/teacher/{id}/course', 'TeacherController@editTeacherCourse')->name('editTeacherCourse');
    Route::put('/teacher/{id}/course', 'TeacherController@setTeacherCourse')->name('setTeacherCourse');
    Route::get('/teacher/{id}/price', 'TeacherController@editTeacherPrice')->name('editTeacherPrice');
    Route::put('/teacher/{id}/price', 'TeacherController@setTeacherPrice')->name('setTeacherPrice');

    // Order Helper
    Route::post('/order/{id}/changestatus', 'OrderController@changeStatus')->name('changeStatusOrder');

});

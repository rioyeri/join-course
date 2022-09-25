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

// invitation
Route::get('/invitation/{invitation_id}/kepada:{receiver}', 'InvitationController@getInvitation')->name('getInvitation');
Route::post('/sendMessageInvitation/{invitation_id}/', 'InvitationController@sendMessage')->name('sendMessageInvitation');

// Template
Route::get('pilihtemplate', 'TemplateController@index');

Route::middleware(['checkUser'])->group(function () {
    // Account
    Route::get('/account/profile','AccountController@profile')->name('showProfile');
    Route::get('/account/change_pass','AccountController@getchange_pass')->name('getChangePass');
    Route::get('/account/change_foto','AccountController@getchange_foto')->name('getChangeFoto');
    Route::post('/account/change_foto','AccountController@change_foto')->name('changeFoto');
    Route::post('/account/change_pass','AccountController@change_pass')->name('changePass');

    // Menu Mapping
    Route::get('/menumapping','MenuController@index')->name('getMapping');
    Route::get('/showmapping/{id}','MenuController@show')->name('showMapping');
    Route::post('/storemapping','MenuController@store')->name('storeMapping');
    Route::post('/deletemapping','MenuController@delete')->name('deleteMapping');

    // Role Mapping
    Route::get('rolemapping','RoleMappingController@index')->name('getRoleMapping');
    Route::get('/rolemapping/{id}/edit','RoleMappingController@edit')->name('editRoleMapping');
    Route::put('/rolemapping/{id}/update','RoleMappingController@update')->name('updateRoleMapping');
    Route::delete('/rolemapping/{id}/delete','RoleMappingController@destroy')->name('destroyRoleMapping');

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
        // Gallery
        'gallery' => 'GalleryController',
        // Link
        'link' => 'LinkController',
        // About Us
        'aboutus' => 'AboutUsController',
        // Banner
        'banner' => 'BannerController',
        // Sosmed
        'sosmed' => 'SosmedController',
        // Course
        'course' => 'CourseController',
        // Course Payment
        'coursepayment' => 'CoursePaymentController',
    ]);

    Route::get('logout','HomeController@logout')->name('Logout');

    // ------------------------ HELPER -------------------------------------------------
    Route::get('/datakota','HelperController@getDataKota')->name('getDataKota');
    Route::get('/datacoa','HelperController@ajxCoa')->name('ajxCoa');
    Route::get('getCheckBeforeDelete', 'HelperController@checkBeforeDelete')->name('checkBeforeDelete');

    // Course Helper
    Route::post('/course/{id}/changestatus', 'CourseController@changeStatus')->name('changeStatusCourse');

    // Gift Box Helper
    Route::get('/addGiftBoxToTable', 'GiftBoxController@addToTable')->name('addGiftBoxToTable');
});

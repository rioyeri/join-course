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
// Route::get('/testuser','TestController@index');
Route::get('/','HomeController@index')->name('getHome');
Route::get('/login','HomeController@index2')->name('getHome2');
Route::post('login','HomeController@login')->name('Login');
Route::get('/register', 'HomeController@get_register')->name('get_register');
Route::post('register', 'HomeController@post_register')->name('post_register');
// User Helper
Route::get('checkUsernameAvailability','UserController@checkUsernameAvailability')->name('checkUsernameAvailability');

// maintenance
Route::get('maintenance', 'HomeController@maintenance')->name('maintenance');

// invitation
Route::get('/invitation/{invitation_id}/kepada:{receiver}', 'InvitationController@getInvitation')->name('getInvitation');
Route::post('/sendMessageInvitation/{invitation_id}/', 'InvitationController@sendMessage')->name('sendMessageInvitation');

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
        // Jadwal
        'jadwal' => 'JadwalController',
        // Gallery
        'gallery' => 'GalleryController',
        // Link
        'link' => 'LinkController',
        // About Us
        'aboutus' => 'AboutUsController',
        // Banner
        'banner' => 'BannerController',
        // Day
        'days' => 'DaysController',
        // Sosmed
        'sosmed' => 'SosmedController',
        // Wedding Invitation
        'invitation' => 'InvitationController',
        // Event
        'event' => 'EventController',
        // Gift
        'giftbox' => 'GiftboxController',
        // Complement
        'complement' => 'ComplementController',
        // Quote
        'quote' => 'QuoteController',
    ]);

    Route::get('logout','HomeController@logout')->name('Logout');

    // ------------------------ HELPER -------------------------------------------------
    Route::get('/datakota','HelperController@getDataKota')->name('getDataKota');
    Route::get('/datacoa','HelperController@ajxCoa')->name('ajxCoa');
    Route::get('getCheckBeforeDelete', 'HelperController@checkBeforeDelete')->name('checkBeforeDelete');

    // Event Helper
    Route::get('/addEventToTable', 'EventController@addToTable')->name('addEventToTable');

    // Gift Box Helper
    Route::get('/addGiftBoxToTable', 'GiftBoxController@addToTable')->name('addGiftBoxToTable');
});

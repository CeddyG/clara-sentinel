<?php

//Sentinel login
Route::get('login', 'CeddyG\ClaraSentinel\Http\Controllers\UserController@login');
Route::post('authenticate', 'CeddyG\ClaraSentinel\Http\Controllers\UserController@authenticate');
Route::get('logout', 'CeddyG\ClaraSentinel\Http\Controllers\UserController@logout');

// Password Reset Routes...
Route::get('password/reset', 'CeddyG\ClaraSentinel\Http\Controllers\UserController@createReminder')->name('password.request');
Route::post('password/email', 'CeddyG\ClaraSentinel\Http\Controllers\UserController@storeReminder')->name('password.email');
Route::get('password/reset/{token}', 'CeddyG\ClaraSentinel\Http\Controllers\UserController@editPassword')->name('password.reset');
Route::post('password/reset', 'CeddyG\ClaraSentinel\Http\Controllers\UserController@updatePassword')->name('password.update');

//User
Route::group(['prefix' => config('clara.sentinel.route.web.prefix'), 'middleware' => config('clara.sentinel.route.web.middleware')], function()
{
    Route::post('user/import', 'CeddyG\ClaraSentinel\Http\Controllers\Admin\UserController@import')->name('admin.user.import');
    Route::resource('user', 'CeddyG\ClaraSentinel\Http\Controllers\Admin\UserController', ['names' => 'admin.user']);
});

Route::group(['prefix' => config('clara.sentinel.route.api.prefix'), 'middleware' => config('clara.user.route.api.middleware')], function()
{
    Route::get('user/index', 'CeddyG\ClaraSentinel\Http\Controllers\Admin\UserController@indexAjax')->name('api.admin.user.index');
	Route::get('user/select', 'CeddyG\ClaraSentinel\Http\Controllers\Admin\UserController@selectAjax')->name('api.admin.user.select');
});

//Role
Route::group(['prefix' => config('clara.group.route.web.prefix'), 'middleware' => config('clara.group.route.web.middleware')], function()
{
    Route::resource('group', 'CeddyG\ClaraSentinel\Http\Controllers\Admin\RoleController', ['names' => 'admin.group']);
});

Route::group(['prefix' => config('clara.group.route.api.prefix'), 'middleware' => config('clara.group.route.api.middleware')], function()
{
    Route::get('group/index', 'CeddyG\ClaraSentinel\Http\Controllers\Admin\RoleController@indexAjax')->name('api.admin.group.index');
	Route::get('group/select', 'CeddyG\ClaraSentinel\Http\Controllers\Admin\RoleController@selectAjax')->name('api.admin.group.select');
});
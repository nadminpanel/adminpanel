<?php

Route::group(['middleware' => ['web'], 'namespace' => '\NAdminPanel\AdminPanel\Controllers'], function () {

    Route::get('logout', 'LoginController@logout');

    Route::get(config('nadminpanel.admin_login_link'), 'UserController@showAdminLoginForm');
    Route::post('admin/login', 'LoginController@login')->name('admin.login');


    Route::group(['middleware' => ['auth', 'admin'], 'prefix' => config('nadminpanel.admin_backend_prefix')], function () {

        Route::get(config('nadminpanel.admin_landing_link'), 'UserController@dashboard')->name('admin.dashboard');

        Route::post('admin.logout', 'LoginController@logout')->name('admin.logout');

        Route::get('admin/archive', 'UserController@indexArchive')->name('admin.archive');
        Route::delete('admin/archive/{archive}', 'UserController@destroyArchive')->name('admin.archive.delete');
        Route::match(['put', 'patch'], 'admin/archive/{archive}', 'UserController@unarchive')->name('admin.archive.unarchive');

        Route::get('user/archive', 'UserController@indexArchive')->name('user.archive');
        Route::delete('user/archive/{archive}', 'UserController@destroyArchive')->name('user.archive.delete');
        Route::match(['put', 'patch'], 'user/archive/{archive}', 'UserController@unarchive')->name('user.archive.unarchive');

        Route::get('role/archive', 'RoleController@indexArchive')->name('role.archive');
        Route::delete('role/archive/{archive}', 'RoleController@destroyArchive')->name('role.archive.delete');
        Route::match(['put', 'patch'], 'role/archive/{archive}', 'RoleController@unarchive')->name('role.archive.unarchive');

        Route::get('permission/archive', 'PermissionController@indexArchive')->name('permission.archive');
        Route::delete('permission/archive/{archive}', 'PermissionController@destroyArchive')->name('permission.archive.delete');
        Route::match(['put', 'patch'], 'permission/archive/{archive}', 'PermissionController@unarchive')->name('permission.archive.unarchive');

        Route::match(['put', 'patch'], 'role/{id}/permission', 'PermissionController@permissionUpdate')->name('role.permission.update');
        Route::get('admin', 'UserController@index')->name('admin.index');
        Route::get('user', 'UserController@index')->name('user.index');

        Route::resource('admin', 'UserController', ['except' => ['index', 'create']]);
        Route::resource('user', 'UserController', ['except' => ['index']]);
        Route::resource('role', 'RoleController');
        Route::resource('permission', 'PermissionController');

    });

});


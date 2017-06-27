<?php

Route::group(['middleware' => ['web'], 'namespace' => '\NAdminPanel\AdminPanel\Controllers'], function () {

    Route::get('logout', 'LoginController@logout');

    Route::get(config('nadminpanel.admin_login_link'), 'UserController@showAdminLoginForm');
    Route::post('admin/login', 'LoginController@login')->name('admin.login');


    Route::group(['middleware' => ['auth', 'admin'], 'prefix' => config('nadminpanel.admin_backend_prefix')], function () {

        Route::get(config('nadminpanel.admin_landing_link'), 'UserController@dashboard')->name('backend.dashboard');

        Route::post('admin.logout', 'LoginController@logout')->name('admin.logout');

        Route::get('admin/archive', 'UserController@indexArchive')->name('admin.archive');
        Route::delete('admin/archive/{archive}', 'UserController@destroyArchive')->name('admin.archive.delete');
        Route::match(['put', 'patch'], 'admin/archive/{archive}', 'UserController@unarchive')->name('admin.archive.unarchive');

        $modules = ['user', 'role', 'permission'];

        foreach ($modules as $module) {
            Route::get($module.'/archive', ucfirst($module).'Controller@indexArchive')->name($module.'.archive');
            Route::delete($module.'/archive/{archive}', ucfirst($module).'Controller@destroyArchive')->name($module.'.archive.delete');
            Route::match(['put', 'patch'], $module.'/archive/{archive}', ucfirst($module).'Controller@unarchive')->name($module.'.archive.unarchive');
        }

        Route::match(['put', 'patch'], 'role/{id}/permission', 'PermissionController@permissionUpdate')->name('role.permission.update');
        Route::get('admin', 'UserController@index')->name('admin.index');
        Route::get('user', 'UserController@index')->name('user.index');

        Route::resource('admin', 'UserController', ['except' => ['index', 'create']]);
        Route::resource('user', 'UserController', ['except' => ['index']]);
        Route::resource('role', 'RoleController');
        Route::resource('permission', 'PermissionController');

    });

});


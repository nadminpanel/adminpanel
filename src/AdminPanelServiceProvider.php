<?php

namespace NAdminPanel\AdminPanel;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use NAdminPanel\AdminPanel\Middleware\AdminMiddleware;
use NAdminPanel\AdminPanel\Models\Permission;
use NAdminPanel\AdminPanel\Models\PermissionLabel;
use NAdminPanel\AdminPanel\Models\Role;

class AdminPanelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'nadminpanel');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadPublishes();
        $this->loadMiddlewares();
        $this->loadEventObservers();
    }

    public function register()
    {
        $this->registerAliases();
        $this->registerProviders();
        $this->registerConfigs();
    }

    private function loadMiddlewares()
    {
        $this->app['router']->aliasMiddleware('admin', AdminMiddleware::class);
    }

    private function loadEventObservers()
    {
        PermissionLabel::created(function ($permission_label) {
            foreach (config('nadminpanel.permission_titles') as $title) {
                $permission = Permission::firstOrCreate(['name' => $title.' '.$permission_label->name]);
            }
        });

        PermissionLabel::updated(function ($permission_label) {
            $old_permission_label = $permission_label->getOriginal();
            foreach (config('nadminpanel.permission_titles') as $title) {
                $permission = Permission::whereName($title.' '.$old_permission_label['name'])->first();
                $permission->name = $title.' '.$permission_label->name;
                $permission->save();
            }
        });

        PermissionLabel::deleted(function ($permission_label) {
            foreach (config('nadminpanel.permission_titles') as $title) {
                if($permission_label->isForceDeleting()) {
                    $permission = Permission::withTrashed()->whereName($title.' '.$permission_label->name)->first()->forceDelete();
                } else {
                    $permission = Permission::whereName($title.' '.$permission_label->name)->first()->delete();
                }
            }
        });

        PermissionLabel::restoring(function ($permission_label) {
            foreach (config('nadminpanel.permission_titles') as $title) {
                $permission = Permission::whereName($title.' '.$permission_label->name)->restore();
            }
        });

    }

    private function loadPublishes()
    {
        $this->publishes([
            __DIR__ . '/public' => public_path(),
            __DIR__ . '/seeds/DeveloperSeeder.php.slug' => database_path('seeds/DeveloperSeeder.php')
        ]);

        $this->publishes([
            __DIR__.'/config/nadminpanel.php' => config_path('nadminpanel.php'),
            __DIR__.'/config/nadminpanel-modules.php' => config_path('nadminpanel-modules.php')
        ], 'config');

    }

    private function registerAliases()
    {
        $aliases = [
            'Active' => 'Pyaesone17\ActiveState\ActiveFacade',
            'EmailChecker' => 'Tintnaingwin\EmailChecker\Facades\EmailChecker',
            'Datatables' => 'Yajra\DataTables\Facades\DataTables',
            'Image' => 'Intervention\Image\Facades\Image'
        ];

        $loader = AliasLoader::getInstance();
        foreach ($aliases as $key => $alias) {
            $loader->alias($key, $alias);
        }
    }

    private function registerProviders()
    {
        $this->app->register('Spatie\Permission\PermissionServiceProvider');
        $this->app->register('Yajra\Datatables\DatatablesServiceProvider');
        $this->app->register('Pyaesone17\ActiveState\ActiveStateServiceProvider');
        $this->app->register('Tintnaingwin\EmailChecker\EmailCheckerServiceProvider');
        $this->app->register('Intervention\Image\ImageServiceProvider');
    }

    private function registerConfigs()
    {
        $this->mergeConfigFrom(__DIR__.'/config/nadminpanel.php', 'nadminpanel');
        $this->mergeConfigFrom(__DIR__.'/config/nadminpanel-modules.php', 'nadminpanel-modules');
    }



}
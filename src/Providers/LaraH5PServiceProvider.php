<?php

namespace LaraH5P\Providers;

use Illuminate\Support\ServiceProvider;
use LaraH5P\Commands\MigrationCommand;
use LaraH5P\Commands\ResetCommand;
use LaraH5P\Helpers\H5pHelper;

class LaraH5PServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/larah5p.php', 'larah5p');

        $this->app->singleton('LaraH5P', function ($app) {
            return new LaraH5P($app);
        });

        $this->app->bind('H5pHelper', function () {
            return new H5pHelper();
        });

        $this->app->singleton('command.larah5p.migration', function () {
            return new MigrationCommand();
        });

        $this->app->singleton('command.larah5p.reset', function () {
            return new ResetCommand();
        });

        $this->commands([
            'command.larah5p.migration',
            'command.larah5p.reset',
        ]);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'larah5p');

        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../../config/larah5p.php' => config_path('larah5p.php'),
            __DIR__.'/../../resources/views' => resource_path('views/'),
            __DIR__.'/../../resources/lang' => resource_path('lang/'),
        ], 'larah5p-config');
        $this->publishes([
            __DIR__.'/../../routes/web.php' => base_path('routes/web.php'),
        ], 'larah5p-routes');

        // $this->publishes([
        //     __DIR__.'/../../src/Http/Controllers/web' => app_path('Http/Controllers/LaraH5P'),
        // ], 'larah5p-controllers');

        // نشر الأحداث
        $this->publishes([
            __DIR__.'/../../src/Events' => app_path('Events/LaraH5P'),
        ], 'larah5p-events');

        $this->publishes([
            __DIR__.'/../../src/Notifications' => app_path('Notifications/LaraH5P'),
        ], 'larah5p-notifications');

        $this->publishes([
            __DIR__.'/../../.env.example' => base_path('.env.example'),
        ], 'larah5p-env');

        $this->publishes([
            __DIR__.'/../../assets' => public_path('vendor/larah5p'),
            app_path('/../vendor/h5p/h5p-core/fonts')      => public_path('assets/vendor/h5p/h5p-core/fonts'),
            app_path('/../vendor/h5p/h5p-core/images')     => public_path('assets/vendor/h5p/h5p-core/images'),
            app_path('/../vendor/h5p/h5p-core/js')         => public_path('assets/vendor/h5p/h5p-core/js'),
            app_path('/../vendor/h5p/h5p-core/styles')     => public_path('assets/vendor/h5p/h5p-core/styles'),
            app_path('/../vendor/h5p/h5p-editor/ckeditor') => public_path('assets/vendor/h5p/h5p-editor/ckeditor'),
            app_path('/../vendor/h5p/h5p-editor/images')   => public_path('assets/vendor/h5p/h5p-editor/images'),
            app_path('/../vendor/h5p/h5p-editor/language') => public_path('assets/vendor/h5p/h5p-editor/language'),
            app_path('/../vendor/h5p/h5p-editor/libs')     => public_path('assets/vendor/h5p/h5p-editor/libs'),
            app_path('/../vendor/h5p/h5p-editor/scripts')  => public_path('assets/vendor/h5p/h5p-editor/scripts'),
            app_path('/../vendor/h5p/h5p-editor/styles')   => public_path('assets/vendor/h5p/h5p-editor/styles'),
        ], 'larah5p-assets');
    }

    public function provides()
    {
        return [
            'command.larah5p.migration',
            'command.larah5p.reset',
        ];
    }
}






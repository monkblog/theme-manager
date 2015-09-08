<?php

namespace ThemeManager;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as ServiceProviderSupport;

class ServiceProvider extends ServiceProviderSupport
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/theme-manager.php' => config_path('theme-manager.php'),
        ], 'theme');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('theme.manager', function () {
            $basePath = Config::get('theme-manager.base_path', null);
            $requiredFields = Config::get('theme-manager.required_fields', []);
            $exceptionOnInvalid = Config::get('theme-manager.exception_on_invalid', false);

            return new ThemeManager((new Starter)->start($basePath, $requiredFields, $exceptionOnInvalid));
        });
    }
}

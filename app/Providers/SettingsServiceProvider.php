<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting\GlobalSettings;
use App\Models\Setting\GlobalSettingModel;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Models\Setting\GlobalSettings', function ($app) {
            return new GlobalSettings(GlobalSettingModel::all());
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(GlobalSettings $settinsInstance)
    {
        \View::share('globalsettings', $settinsInstance);
    }
}

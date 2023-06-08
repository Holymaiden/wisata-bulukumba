<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Makassar');

        $models = array(
            'User',
            'Wisata',
            'Suka',
            'Riwayat'
        );

        foreach ($models as $model) {
            $this->app->bind("App\Services\Contracts\\{$model}Contract", "App\Services\\{$model}Service");
        }
    }
}

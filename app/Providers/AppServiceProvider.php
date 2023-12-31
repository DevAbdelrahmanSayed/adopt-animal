<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\TelescopeServiceProvider;
use Illuminate\Support\Facades\Schema;
use Mockery\Mock;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    //   Model::preventLazyLoading(! app()->isProduction());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::macro('toTurkey', function (){
            return $this->format('Y-m-d H:i:s');
        });
        Schema::defaultStringLength(191);
    }
}

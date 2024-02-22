<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\TelescopeServiceProvider;
use Modules\Post\app\Models\Post;
use Modules\Post\Observers\PostObserver;
use Modules\User\app\Models\User;
use Modules\User\Observers\UserObserver;

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
        Model::shouldBeStrict(! app()->isProduction());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::macro('toTurkey', function () {
            return $this->format('Y-m-d H:i:s');
        });
        Schema::defaultStringLength(191);
        Post::observe(PostObserver::class);
        User::observe(UserObserver::class);

    }
}

<?php

namespace App\Providers;

use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        view()->composer('*', function ($view) {
            $settings = Settings::latest()->first();

            if (Auth::user() && Auth::user()->is_dark) {
                $mode = 'dark';
            } else {
                $mode = 'light';
            }

            $view->with(['mode' => $mode, 'settings' => $settings]);
        });
    }
}

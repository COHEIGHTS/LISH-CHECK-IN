<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share pending user count with admin layouts
        View::composer(['layouts.admin', 'layouts.app-dashboard'], function ($view) {
            $pendingCount = User::where('status', 'pending')->count();
            $view->with('pendingCount', $pendingCount);
        });

        // Share pending leave count with admin layouts
        View::composer(['layouts.admin', 'layouts.app-dashboard'], function ($view) {
            $pendingLeaveCount = \App\Models\Leave::where('status', 'pending')->count();
            $view->with('pendingLeaveCount', $pendingLeaveCount);
        });
    }
}

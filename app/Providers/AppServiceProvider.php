<?php

namespace App\Providers;

use App\Policies\RolePolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

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
        // Shield's RolePolicy is outside Laravel's policy auto-discovery.
        Gate::policy(Role::class, RolePolicy::class);

        // Slows down anyone trying to guess family links.
        RateLimiter::for('family-link', fn (Request $request) => Limit::perMinute(30)->by($request->ip()));
    }
}

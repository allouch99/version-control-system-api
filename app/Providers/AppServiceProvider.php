<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Policies\InvitationPolicy;
use App\Policies\FilePolicy;
use Illuminate\Support\Facades\Gate;

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
        Gate::define('create-invitation', [InvitationPolicy::class, 'create']);
        Gate::define('create-file', [FilePolicy::class, 'create']);
    }
}

<?php

namespace App\Providers;

use App\Models\File;
use App\Models\Group;
use App\Models\User;
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
        Gate::define('get-users', [InvitationPolicy::class, 'getUsers']);
        Gate::define('get-user-report', function (User $user, Group $group) {

            return $user->id === $group->user_id;
        });
        Gate::define('get-file-report', function (User $user, File $file) {

            return  $user->id === $file->group->user_id ||$file->group->memberships->pluck('id')->contains($user->id) ;
        });
    }
}

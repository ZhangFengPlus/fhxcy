<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Models\Admin::observe('App\Observers\AdminObserver');
        \App\Models\Auth_group::observe('App\Observers\AuthGroupObserver');
        \App\Models\User::observe('App\Observers\UserObserver');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

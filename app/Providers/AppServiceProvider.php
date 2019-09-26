<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Subject\SubjectInterface as SubjectInterface;
use App\Repositories\Subject\Service as SubjectService;
use App\Repositories\User\UserInterface as UserInterface;
use App\Repositories\User\Service as UserService;

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
        $this->app->singleton(SubjectInterface::class,SubjectService::class);
        $this->app->singleton(UserInterface::class,UserService::class);
    }
}

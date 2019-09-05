<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Subject\Repository as SubjectRepository;
use App\Repositories\Subject\Eloquent as SubjectEloquent;

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
        $this->app->singleton(SubjectRepository::class,SubjectEloquent::class);
    }
}

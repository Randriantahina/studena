<?php

namespace App\Providers;

use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Contracts\TutorRepositoryInterface;
use App\Repositories\StudentRepository;
use App\Repositories\TutorRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TutorRepositoryInterface::class, TutorRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

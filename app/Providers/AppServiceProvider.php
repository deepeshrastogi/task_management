<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Users\UserRepository;
use App\Repositories\Interfaces\Users\UserRepositoryInterface;
use App\Repositories\Tasks\TaskRepository;
use App\Repositories\Interfaces\Tasks\TaskRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerUserRepo();
        $this->registerTaskRepo();
    }

    public function registerUserRepo() {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    public function registerTaskRepo() {
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

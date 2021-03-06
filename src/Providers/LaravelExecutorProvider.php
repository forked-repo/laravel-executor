<?php

namespace AshAllenDesign\LaravelExecutor\Providers;

use AshAllenDesign\LaravelExecutor\Classes\Executor;
use AshAllenDesign\LaravelExecutor\Console\Commands\ExecutorMakeCommand;
use Illuminate\Support\ServiceProvider;

class LaravelExecutorProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->alias(Executor::class, 'laravel-executor');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ExecutorMakeCommand::class,
            ]);
        }
    }
}

<?php

namespace Alive2212\LaravelMessageService;

use Alive2212\LaravelMessageService\Console\InstallCommand;
use Alive2212\LaravelMessageService\Providers\AliveLaravelMessageServiceRouteServiceProvider;
use Illuminate\Support\ServiceProvider;

class LaravelMessageServiceServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(AliveLaravelMessageServiceRouteServiceProvider::class);

        if ($this->app->runningInConsole()) {

            // register jobs
            $this->registerJobs();

            // register migrations
            $this->registerMigrations();

            // register all commands into 'Console' folder
            $this->registerCommands();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Register Package's migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if (LaravelMessageService::$runsMigrations) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
            return;
        }
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'message-service');
    }

    /**
     * Register a database migration path.
     *
     * @param  array|string $paths
     * @return void
     */
    protected function loadMigrationsFrom($paths)
    {
        $this->app->afterResolving('migrator', function ($migrator) use ($paths) {
            foreach ((array)$paths as $path) {
                $migrator->path($path);
            }
        });
    }

    public function registerCommands()
    {
        $consoles = scandir(__DIR__ . '/Console');
        $commands = [];
        unset($consoles[0], $consoles[1]);
        foreach ($consoles as $console) {
            $dir = __NAMESPACE__ . '\\Console\\' . str_replace(".php", "", $console);
            array_push($commands, $dir);
        }
        $this->commands($commands);
    }

    /**
     *
     */
    public function registerJobs()
    {
        $this->publishes([
            __DIR__ . '/Jobs' => app_path('Jobs'),
        ], "message-service");
    }
}
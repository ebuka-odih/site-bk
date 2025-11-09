<?php

namespace App\Providers;

use App\Services\BankingService;
use Illuminate\Support\ServiceProvider;

class BankingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BankingService::class, function ($app) {
            return new BankingService();
        });

        // Merge the banking config
        $this->mergeConfigFrom(
            __DIR__.'/../../config/banking.php', 'banking'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish the config file
        $this->publishes([
            __DIR__.'/../../config/banking.php' => config_path('banking.php'),
        ], 'banking-config');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                // Register console commands here if needed
            ]);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [BankingService::class];
    }
}

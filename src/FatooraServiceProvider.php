<?php

namespace Saudiza\Fatoora;

use Illuminate\Support\ServiceProvider;
use Saudiza\Fatoora\Console\ZatcaInstallCommand;
use Saudiza\Fatoora\Console\ZatcaOnboardingCommand;

class FatooraServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/zatca.php' => config_path('zatca.php'),
        ], 'zatca-config');

        // Publish language files
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/zatca'),
        ], 'zatca-lang');

        // Load translations
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'zatca');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                ZatcaInstallCommand::class,
                ZatcaOnboardingCommand::class,
            ]);
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__.'/../config/zatca.php',
            'zatca'
        );

        // Register singleton for OnBoarding
        $this->app->singleton('zatca.onboarding', function ($app) {
            return new OnBoarding();
        });

        // Register singleton for InvoiceGenerator
        $this->app->singleton('zatca.invoice', function ($app) {
            return new Invoice\InvoiceGenerator();
        });

        // Register main Fatoora class
        $this->app->singleton('fatoora', function ($app) {
            return new Fatoora();
        });

        // Alias for backward compatibility
        $this->app->alias('fatoora', 'zatca');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'fatoora',
            'zatca', // Backward compatibility
            'zatca.onboarding',
            'zatca.invoice',
        ];
    }
}

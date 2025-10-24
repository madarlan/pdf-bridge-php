<?php

namespace MadArlan\PDFBridge\Laravel;

use Illuminate\Support\ServiceProvider;
use MadArlan\PDFBridge\Console\PDFConvertCommand;
use MadArlan\PDFBridge\PDFBridge;

/**
 * Laravel Service Provider for PDF Bridge
 */
class PDFBridgeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__.'/../../config/pdf-bridge.php',
            'pdf-bridge'
        );

        // Register PDFBridge as singleton
        $this->app->singleton('pdf-bridge', function ($app) {
            $config = $app['config']->get('pdf-bridge', []);

            return new PDFBridge($config);
        });

        // Register alias
        $this->app->alias('pdf-bridge', PDFBridge::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/pdf-bridge.php' => config_path('pdf-bridge.php'),
            ], 'pdf-bridge-config');

            // Publish all files at once
            $this->publishes([
                __DIR__.'/../../config/pdf-bridge.php' => config_path('pdf-bridge.php'),
            ], 'pdf-bridge');

            // Register Artisan commands
            $this->commands([
                PDFConvertCommand::class,
            ]);
        }
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            'pdf-bridge',
            PDFBridge::class,
        ];
    }
}

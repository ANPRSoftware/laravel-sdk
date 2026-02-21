<?php

namespace Anpr\LaravelSdk;

use Illuminate\Support\ServiceProvider;
use Anpr\LaravelSdk\Contracts\AnprClientInterface;
use Anpr\LaravelSdk\Http\HttpTransport;

class AnprServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/anpr.php', 'anpr');

        $this->app->singleton(AnprClientInterface::class, function ($app) {
            $config = $app['config']['anpr'];

            $transport = new HttpTransport(
                baseUrl:      $config['base_url'],
                apiKey:       $config['api_key'],
                timeout:      $config['timeout'],
                retryTimes:   $config['retries']['times'],
                retrySleepMs: $config['retries']['sleep_ms'],
                retryWhen:    $config['retries']['when'],
            );

            return new AnprClient($transport, $config['api_version'] ?? 'v1');
        });

        $this->app->alias(AnprClientInterface::class, 'anpr');
        $this->app->alias(AnprClientInterface::class, AnprClient::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/anpr.php' => config_path('anpr.php'),
            ], 'anpr-config');

            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations'),
            ], 'anpr-migrations');
        }

    }
}

<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad;

use Illuminate\Support\ServiceProvider;
use Gumroad\Clients\GumroadClient;

class GumroadServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/gumroad.php', 'gumroad');
        
        $this->app->singleton('gumroad', function ($app) {
            $config = $app['config']['gumroad'];
            return new GumroadClient($config);
        });
        
        $this->app->alias('gumroad', GumroadClient::class);
    }
    
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/gumroad.php' => config_path('gumroad.php'),
            ], 'config');
        }
    }
}
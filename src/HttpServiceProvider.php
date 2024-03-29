<?php

namespace Trigold\Http;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Trigold\Http\Client\Guzzle;

class HttpServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->alias(Guzzle::class, 'http.client');
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
    }
}

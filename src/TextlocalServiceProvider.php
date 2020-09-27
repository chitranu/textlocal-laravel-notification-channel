<?php

namespace NotificationChannels\Textlocal;

use Illuminate\Support\ServiceProvider;

class TextlocalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(TextlocalChannel::class)
            ->needs(Textlocal::class)
            ->give(function () {
                return new Textlocal(
                    $this->app['config']['services.textlocal.key'],
                    $this->app['config']['services.textlocal.sender']
                );
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}

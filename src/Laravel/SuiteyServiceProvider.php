<?php

namespace TheCrypticAce\Suitey\Laravel;

use TheCrypticAce\Suitey\Suitey;
use TheCrypticAce\Suitey\Process;
use TheCrypticAce\Suitey\Console\Test;
use Illuminate\Support\ServiceProvider;

class SuiteyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Test::class,
            ]);
        }
    }

    public function register()
    {
        $this->app->singleton(Suitey::class);

        Process::useArtisanPath(realpath($this->app->basePath("artisan")));
    }
}

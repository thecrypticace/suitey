<?php

namespace Tests;

use TheCrypticAce\Suitey\Suitey;
use Orchestra\Testbench\TestCase as BaseTestCase;
use TheCrypticAce\Suitey\Laravel\SuiteyServiceProvider;

class TestCase extends BaseTestCase
{
    use Concerns\InteractsWithConsole;

    public function setUp()
    {
        parent::setUp();

        $this->suitey = $this->app->make(Suitey::class);;

        $_SERVER["argv"] = ["artisan", "test", "--filter", "stub"];
    }

    protected function getPackageProviders($app)
    {
        return [
            SuiteyServiceProvider::class,
        ];
    }
}

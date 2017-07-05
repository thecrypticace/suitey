<?php

namespace Tests;

use TheCrypticAce\Suitey\Suitey;
use TheCrypticAce\Suitey\Process;
use Orchestra\Testbench\TestCase as BaseTestCase;
use TheCrypticAce\Suitey\Laravel\SuiteyServiceProvider;

class TestCase extends BaseTestCase
{
    use Concerns\InteractsWithConsole;
    use Concerns\SetUpEnvironment;

    public function setUp()
    {
        parent::setUp();

        $this->suitey = $this->app->make(Suitey::class);;

        $_SERVER["argv"] = ["artisan", "test", "--filter", "stub"];

        Process::useArtisanPath(__DIR__."/Fixture/artisan");
    }

    public function __sleep()
    {
        return [];
    }

    protected function getPackageProviders($app)
    {
        return [
            SuiteyServiceProvider::class,
        ];
    }
}

<?php

namespace Tests\Concerns;

use TheCrypticAce\Suitey\Environment\Dotenv;

trait SetUpEnvironment
{
    protected function getEnvironmentSetUp($app)
    {
        $app->make(Dotenv::class)->load(realpath(__DIR__."/../Fixture/.env"));
        $app->useDatabasePath(realpath(__DIR__."/../Fixture/database"));

        $files = $this->getConfigurationFiles();

        foreach ($this->getConfigurationFiles() as $key => $path) {
            $app["config"]->set($key, array_replace_recursive(
                $app["config"]->get($key),
                require $path
            ));
        }
    }

    private function getConfigurationFiles()
    {
        foreach (glob(__DIR__."/../Fixture/config/*.php") as $file) {
            yield basename($file, ".php") => realpath($file);
        }
    }
}

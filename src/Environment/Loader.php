<?php

namespace TheCrypticAce\Suitey\Environment;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Container\Container;

class Loader
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function load($envs)
    {
        $envs = new Collection($envs);

        $envs = $envs->filter(function ($env) {
            return file_exists($env["path"]);
        });


        $envs->each(function ($env) {
            $this->container->make($env["loader"])->load($env["path"]);
        });
    }
}

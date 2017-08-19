<?php

namespace TheCrypticAce\Suitey\Environment;

use Illuminate\Contracts\Foundation\Application;

final class Environment
{
    public static function loadUsing(Application $app)
    {
        return $app->make(Loader::class)->load(
            $app["config"]["suitey.environments"]
        );
    }

    public static function apply($environment)
    {
        foreach ($environment as $key => $values) {
            foreach ((array) $values as $value) {
                putenv("{$key}={$value}");
            }
        }
    }
}

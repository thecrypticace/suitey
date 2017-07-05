<?php

namespace TheCrypticAce\Suitey\Environment;

use PHPUnit_Util_Configuration as PHPUnit5Config;
use PHPUnit\Util\Configuration as PHPUnit6Config;

class PHPUnit
{
    public function load($file)
    {
        $configClass = class_exists(PHPUnit6Config::class, true)
            ? PHPUnit6Config::class
            : PHPUnit5Config::class;

        $config = $configClass::getInstance($file)->getPHPConfiguration();

        return Environment::apply($config["env"] ?? []);
    }
}

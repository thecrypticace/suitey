<?php

namespace TheCrypticAce\Suitey\Environment;

use PHPUnit\Util\Configuration;

class PHPUnit
{
    public function load($file)
    {
        $config = Configuration::getInstance($file)->getPHPConfiguration();

        return Environment::apply($config["env"] ?? []);
    }
}

<?php

namespace TheCrypticAce\Suitey\Environment;

use Dotenv\Dotenv as DotenvLoader;

class Dotenv
{
    public function load($file)
    {
        $dotenv = new DotenvLoader(dirname($file), basename($file));
        $dotenv->load();
    }
}

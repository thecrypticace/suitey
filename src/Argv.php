<?php

namespace TheCrypticAce\Suitey;

use Illuminate\Support\Collection;

class Argv
{
    private $argv;

    public function __construct($argv)
    {
        $this->argv = new Collection($argv);
    }

    public function all()
    {
        return $this->argv->all();
    }

    public function slice($index, $length = null)
    {
        return new static($this->argv->slice($index, $length)->values());
    }

    public function options()
    {
        $index = $this->argv->search("--");

        return $index !== false
            ? $this->slice($index + 1)
            : $this;
    }
}

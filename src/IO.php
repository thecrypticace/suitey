<?php

namespace TheCrypticAce\Suitey;

class IO
{
    public function __construct(array $argv, $input, $output)
    {
        $this->argv = new Argv($argv);
        $this->input = $input;
        $this->output = $output;
    }

    public function argv()
    {
        return $this->argv;
    }

    public function input()
    {
        return $this->input;
    }

    public function output()
    {
        return $this->output;
    }
}

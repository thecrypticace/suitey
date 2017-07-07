<?php

namespace Tests\Concerns;

use PHPUnit\Framework\Assert;
use Illuminate\Support\Collection;

class ArtisanResult
{
    private $output;
    private $status;
    private $parameters;

    public function __construct($parameters, $output, $status)
    {
        $this->output = $output;
        $this->status = $status;
        $this->parameters = $parameters;
        $this->assertableOutput = preg_replace("/[\s\n]+/", " ", $this->output);
    }

    public function assertStatus($expected)
    {
        Assert::assertEquals($expected, $this->status);
    }

    public function assertOutputContains($expected)
    {
        foreach ((array) $expected as $line) {
            Assert::assertContains($line, $this->assertableOutput);
        }
    }

    public function dump()
    {
        return tap($this, function () {
            dump("Status: {$this->status}");
            dump("Output:\n{$this->output}");
        });
    }

    public function dumpIfFailed()
    {
        return $this->status === 0 ? $this : $this->dump();
    }

    private function lines()
    {
        $lines = new Collection(explode("\n", trim($this->output)));
        $lines = $lines->map(function ($line) {
            return trim($line);
        });

        return $lines;
    }
}

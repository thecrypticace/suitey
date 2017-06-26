<?php

namespace Tests\Concerns;

use PHPUnit\Framework\Assert;

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

        $this->dump();
    }

    public function assertStatus($expected)
    {
        Assert::assertEquals($expected, $this->status);
    }

    public function assertStepOutput($expectedLines)
    {
        foreach ($this->stepLines() as $index => $actualLine) {
            Assert::assertEquals($expectedLines[$index], $actualLine);
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

    private function stepLines()
    {
        foreach (explode("\n", $this->output) as $index => $actualLine) {
            if (substr($actualLine, 0, 1) !== "[") {
                break;
            }

            yield $index => $actualLine;
        }
    }
}

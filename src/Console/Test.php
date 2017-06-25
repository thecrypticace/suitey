<?php

namespace TheCrypticAce\Suitey\Console;

use TheCrypticAce\Suitey\IO;
use Illuminate\Console\Command;
use TheCrypticAce\Suitey\Steps;
use TheCrypticAce\Suitey\Suitey;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Test extends Command
{
    protected $signature = "test {--test-only : Only run PHPUnit}";
    protected $description = "Run tests for the application";

    public function __construct()
    {
        parent::__construct();

        $this->ignoreValidationErrors();
    }

    public function handle(Suitey $suitey)
    {
        if ($this->option("test-only")) {
            $suitey = $suitey->fresh();
        }

        $suitey->add(new Steps\RunPHPUnit);

        try {
            return $suitey->run(
                new IO($_SERVER["argv"], $this->input, $this->output)
            );
        } catch (ProcessFailedException $e) {
            return $e->getProcess()->getExitCode();
        }
    }
}

<?php

namespace TheCrypticAce\Suitey;

use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process as SymfonyProcess;
use Symfony\Component\Process\Exception\RuntimeException;

class Process
{
    private $tty;
    private $output;
    private $status;
    private $binary;
    private $arguments;
    private $environment;

    private static $artisanPath;
    private static $sharedOutput;

    public function __construct($binary, $arguments, $environment = null)
    {
        static::$sharedOutput = static::$sharedOutput ?? new NullOutput;

        $this->tty = false;
        $this->binary = [PHP_BINARY, $binary];
        $this->status = null;
        $this->output = static::$sharedOutput;
        $this->arguments = $arguments;
        $this->environment = $environment ?? ["APP_ENV" => "testing"];
    }

    public static function binary($binary, $arguments = [])
    {
        return new static($binary, $arguments);
    }

    public static function artisan($command, $arguments = [])
    {
        return static::binary(static::$artisanPath, array_merge((array) $command, $arguments));
    }

    public static function useArtisanPath($newPath)
    {
        static::$artisanPath = $newPath;
    }

    public static function usingOutput(OutputInterface $output, $callback)
    {
        $oldOutput = static::$sharedOutput;
        static::$sharedOutput = $output;

        return tap($callback(), function () use ($oldOutput) {
            static::$sharedOutput = $oldOutput;
        });
    }

    public function useOutput(OutputInterface $output)
    {
        $this->output = $output;

        return $this;
    }

    public function useTty($use = null)
    {
        $this->tty = (bool) ($use ?? true);

        return $this;
    }

    public function quiet()
    {
        return $this->useOutput(new NullOutput);
    }

    public function run($arguments = [])
    {
        $arguments = array_merge($this->binary, $this->arguments, $arguments);

        $process = new SymfonyProcess($arguments, null, $this->environment);
        $process->inheritEnvironmentVariables(true);

        try {
            if (! $this->output instanceof NullOutput && $this->tty) {
                $process->setTty(true);
            }
        } catch (RuntimeException $e) {
            $this->output->writeln("Warning: {$e->getMessage()}");
        }

        return $this->status = $process->mustRun(function ($type, $line) {
            $this->output->write($line);
        });
    }
}

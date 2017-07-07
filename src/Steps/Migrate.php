<?php

namespace TheCrypticAce\Suitey\Steps;

use Closure;
use TheCrypticAce\Suitey\IO;
use TheCrypticAce\Suitey\Process;

class Migrate implements Step
{
    private $path;
    private $database;

    public function __construct(array $options = [])
    {
        $this->path = $options["path"] ?? null;
        $this->database = $options["database"] ?? null;
    }

    public function name()
    {
        return $this->database
            ? "Migrate {$this->database}"
            : "Migrate database";
    }

    public function handle(IO $io, Closure $next)
    {
        $arguments = [];

        if ($this->path) {
            $arguments[] = "--path={$this->path}";
        }

        if ($this->database) {
            $arguments[] = "--database={$this->database}";
        }

        Process::artisan("migrate")->quiet()->run($arguments);

        $exitCode = $next($io);

        Process::artisan("migrate:rollback")->quiet()->run($arguments);

        return $exitCode;
    }
}

<?php

namespace Tests\Fixture\App\Steps;

use Closure;
use TheCrypticAce\Suitey\IO;
use TheCrypticAce\Suitey\Steps\Step;

class Stub implements Step
{
    private $name;

    public function __construct(array $options = [])
    {
        $this->name = $options["name"] ?? "-";
    }

    public function name()
    {
        return "stub {$this->name}";
    }

    public function handle(IO $io, Closure $next)
    {
        return $next($io);
    }
}

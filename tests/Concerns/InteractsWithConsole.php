<?php

namespace Tests\Concerns;

use Illuminate\Contracts\Console\Kernel;
use Symfony\Component\Console\Output\BufferedOutput;

trait InteractsWithConsole
{
    public function artisan($command, $parameters = [])
    {
        $console = $this->app[Kernel::class];

        $status = $console->call($command, $parameters, $output = new BufferedOutput);

        return new ArtisanResult($parameters, $output->fetch(), $status);
    }
}

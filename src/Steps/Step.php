<?php

namespace TheCrypticAce\Suitey\Steps;

use Closure;
use TheCrypticAce\Suitey\IO;

interface Step
{
    /**
     * The name of the step as displayed on screen
     *
     * @return string
     */
    public function name();

    /**
     * Run the step.
     *
     * @return int a Unix-like status code to indicate success
     */
    public function handle(IO $io, Closure $next);
}

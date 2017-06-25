<?php

namespace TheCrypticAce\Suitey\Laravel;

use Illuminate\Support\Facades\Facade;

/**
 * @see \TheCrypticAce\Suitey\Suitey
 */
class Suitey extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \TheCrypticAce\Suitey\Suitey::class;
    }
}

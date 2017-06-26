<?php

namespace Tests\Feature\Steps;

use Tests\TestCase as BaseTestCase;
use TheCrypticAce\Suitey\Steps;

class TestCase extends BaseTestCase
{
    protected function relativeFixturePath()
    {
        return "../../../../tests/Fixture";
    }
}

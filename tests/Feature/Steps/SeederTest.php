<?php

namespace Tests\Feature\Steps;

use Tests\TestCase;
use TheCrypticAce\Suitey\Steps;

class SeederTest extends TestCase
{
    /** @!test */
    public function it_can_run_a_given_seeder()
    {
        $this->suitey->add(new Steps\RunSeeder("TestSeeder"));

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertStepOutput([
            "[1/2] Run seeder TestSeeder",
            "[2/2] Run PHPUnit",
        ]);

        // Check for database changes
    }
}

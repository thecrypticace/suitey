<?php

namespace Tests\Feature\Steps;

use Tests\TestCase;
use TheCrypticAce\Suitey\Steps;

class SeedDatabaseTest extends TestCase
{
    /** @test */
    public function it_can_run_a_given_seeder()
    {
        $this->suitey->add(new Steps\Migrate());
        $this->suitey->add(new Steps\SeedDatabase("TestSeeder"));
        $this->suitey->add(new Steps\RunCode("Asserting", function () {
            $this->assertDatabaseHas("default_tests", ["id" => 2]);
        }));

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertStepOutput([
            "[1/4] Migrate database",
            "[2/4] Seed database using TestSeeder",
            "[3/4] Asserting",
            "[4/4] Run PHPUnit",
        ]);
    }
}

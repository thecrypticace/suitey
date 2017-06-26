<?php

namespace Tests\Feature\Steps;

use Tests\TestCase;
use TheCrypticAce\Suitey\Steps;

class MigrateTest extends TestCase
{
    /** @test */
    public function it_can_migrate_the_default_database()
    {
        $this->suitey->add(new Steps\Migrate);
        $this->suitey->add(new Steps\RunCode("Asserting", function () {
            $this->assertDatabaseHas("default_tests", ["id" => 1]);
        }));

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertStepOutput([
            "[1/3] Migrate database",
            "[2/3] Asserting",
            "[3/3] Run PHPUnit",
        ]);
    }

    /** @test */
    public function it_can_migrate_a_custom_database()
    {
        $this->suitey->add(new Steps\Migrate("foo"));
        $this->suitey->add(new Steps\RunCode("Asserting", function () {
            $this->assertDatabaseHas("default_tests", ["id" => 1], "foo");
        }));

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertStepOutput([
            "[1/3] Migrate foo",
            "[2/3] Asserting",
            "[3/3] Run PHPUnit",
        ]);
    }

    /** @test */
    public function it_can_migrate_a_custom_database_with_a_custom_path()
    {
        $this->suitey->add(new Steps\Migrate("bar", "{$this->relativeFixturePath()}/database/migrations/bar"));
        $this->suitey->add(new Steps\RunCode("Asserting", function () {
            $this->assertDatabaseHas("bar_tests", ["id" => 1], "bar");
        }));

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertStepOutput([
            "[1/3] Migrate bar",
            "[2/3] Asserting",
            "[3/3] Run PHPUnit",
        ]);
    }

    private function relativeFixturePath()
    {
        return "../../../../tests/Fixture";
    }
}

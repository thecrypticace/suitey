<?php

namespace Tests\Feature\Steps;

use TheCrypticAce\Suitey\Steps;

class RefreshDatabaseTest extends TestCase
{
    /** @test */
    public function it_can_refresh_the_default_database()
    {
        $this->suitey->add(new Steps\RefreshDatabase);
        $this->suitey->add(new Steps\RunCode("Asserting", function () {
            $this->assertDatabaseHas("default_tests", ["id" => 1]);
        }));

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertStepOutput([
            "[1/3] Refreshing database",
            "[2/3] Asserting",
            "[3/3] Run PHPUnit",
        ]);
    }

    /** @test */
    public function it_can_refresh_a_custom_database()
    {
        $this->suitey->add(new Steps\RefreshDatabase("foo"));
        $this->suitey->add(new Steps\RunCode("Asserting", function () {
            $this->assertDatabaseHas("default_tests", ["id" => 1], "foo");
        }));

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertStepOutput([
            "[1/3] Refreshing foo",
            "[2/3] Asserting",
            "[3/3] Run PHPUnit",
        ]);
    }

    /** @test */
    public function it_can_refresh_a_custom_database_with_a_custom_path()
    {
        $this->suitey->add(new Steps\RefreshDatabase("bar", "{$this->relativeFixturePath()}/database/migrations/bar"));
        $this->suitey->add(new Steps\RunCode("Asserting", function () {
            $this->assertDatabaseHas("bar_tests", ["id" => 1], "bar");
        }));

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertStepOutput([
            "[1/3] Refreshing bar",
            "[2/3] Asserting",
            "[3/3] Run PHPUnit",
        ]);
    }

    /** @test */
    public function it_can_refresh_multiple_sets_of_migrations_with_duplicate_class_names()
    {
        $this->suitey->add(new Steps\RefreshDatabase("foo", "{$this->relativeFixturePath()}/database/migrations/dup1"));
        $this->suitey->add(new Steps\RefreshDatabase("bar", "{$this->relativeFixturePath()}/database/migrations/dup2"));
        $this->suitey->add(new Steps\RunCode("Asserting", function () {
            $this->assertDatabaseHas("dup1_tests", ["id" => 1], "foo");
            $this->assertDatabaseHas("dup2_tests", ["id" => 1], "bar");
        }));

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertStepOutput([
            "[1/4] Refreshing foo",
            "[2/4] Refreshing bar",
            "[3/4] Asserting",
            "[4/4] Run PHPUnit",
        ]);
    }
}

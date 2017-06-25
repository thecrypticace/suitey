<?php

namespace Tests\Feature\Steps;

use Tests\TestCase;
use TheCrypticAce\Suitey\Steps;

class MigrateTest extends TestCase
{
    /** @test */
    public function it_can_migrate_the_default_database()
    {
        $this->markTestIncomplete("TODO: Artisan CLI not set up for testing yet");

        $this->suitey->add(new Steps\Migrate);

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertStepOutput([
            "[1/2] Migrate database",
            "[2/2] Run PHPUnit",
        ]);

        // Check for database tables
    }

    /** @test */
    public function it_can_migrate_a_custom_database()
    {
        $this->markTestIncomplete("TODO: Artisan CLI not set up for testing yet");

        $this->suitey->add(new Steps\Migrate("foo"));

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertStepOutput([
            "[1/2] Migrate foo",
            "[2/2] Run PHPUnit",
        ]);

        // Check for database tables
    }

    /** @test */
    public function it_can_migrate_a_custom_database_with_a_custom_path()
    {
        $this->markTestIncomplete("TODO: Artisan CLI not set up for testing yet");

        $this->suitey->add(new Steps\Migrate("bar", "database/migrations/bar"));

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertStepOutput([
            "[1/2] Migrate bar",
            "[2/2] Run PHPUnit",
        ]);

        // Check for database tables
    }
}

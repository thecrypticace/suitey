<?php

namespace Tests\Feature\Steps;

use TheCrypticAce\Suitey\Steps;

class SeedDatabaseTest extends TestCase
{
    use RunStepTests;

    protected function name()
    {
        return "seed database";
    }

    protected function steps()
    {
        yield "using a given seeder" => [
            "steps" => [
                [
                    "class" => Steps\Migrate::class,
                    "options" => [],
                ],
                [
                    "class" => Steps\SeedDatabase::class,
                    "options" => ["class" => "TestSeeder"],
                ],
            ],

            "status" => 0,
            "output" => [
                "[1/4] Migrate database",
                "[2/4] Seed database using TestSeeder",
                "[3/4] Asserting",
                "[4/4] Run PHPUnit",
            ],

            "database" => [
                ["connection" => null, "table" => "default_tests", "attributes" => ["id" => 2]],
            ],
        ];
    }
}

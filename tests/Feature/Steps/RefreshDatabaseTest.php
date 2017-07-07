<?php

namespace Tests\Feature\Steps;

use TheCrypticAce\Suitey\Steps;

class RefreshDatabaseTest extends TestCase
{
    use RunStepTests;

    protected function name()
    {
        return "refresh database";
    }

    protected function steps()
    {
        yield "default database" => [
            "steps" => [
                [
                    "class" => Steps\RefreshDatabase::class,
                    "options" => [],
                ],
            ],

            "status" => 0,
            "output" => [
                "[1/3] Refreshing database",
                "[2/3] Asserting",
                "[3/3] Run PHPUnit",
            ],

            "database" => [
                ["connection" => null, "table" => "default_tests", "attributes" => ["id" => 1]],
            ],
        ];

        yield "custom database" => [
            "steps" => [
                [
                    "class" => Steps\RefreshDatabase::class,
                    "options" => ["database" => "foo"],
                ],
            ],

            "status" => 0,
            "output" => [
                "[1/3] Refreshing foo",
                "[2/3] Asserting",
                "[3/3] Run PHPUnit",
            ],

            "database" => [
                ["connection" => "foo", "table" => "default_tests", "attributes" => ["id" => 1]],
            ],
        ];

        yield "custom database + custom path" => [
            "steps" => [
                [
                    "class" => Steps\RefreshDatabase::class,
                    "options" => ["database" => "bar", "path" => "{$this->relativeFixturePath()}/database/migrations/bar"],
                ],
            ],

            "status" => 0,
            "output" => [
                "[1/3] Refreshing bar",
                "[2/3] Asserting",
                "[3/3] Run PHPUnit",
            ],

            "database" => [
                ["connection" => "bar", "table" => "bar_tests", "attributes" => ["id" => 1]],
            ],
        ];

        yield "multiple migrations with duplicate class names" => [
            "steps" => [
                [
                    "class" => Steps\RefreshDatabase::class,
                    "options" => ["database" => "foo", "path" => "{$this->relativeFixturePath()}/database/migrations/dup1"],
                ],
                [
                    "class" => Steps\RefreshDatabase::class,
                    "options" => ["database" => "bar", "path" => "{$this->relativeFixturePath()}/database/migrations/dup2"],
                ],
            ],

            "status" => 0,
            "output" => [
                "[1/4] Refreshing foo",
                "[2/4] Refreshing bar",
                "[3/4] Asserting",
                "[4/4] Run PHPUnit",
            ],

            "database" => [
                ["connection" => "foo", "table" => "dup1_tests", "attributes" => ["id" => 1]],
                ["connection" => "bar", "table" => "dup2_tests", "attributes" => ["id" => 1]],
            ],
        ];
    }
}

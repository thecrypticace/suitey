<?php

namespace Tests\Feature\Steps;

use Tests\TestCase as BaseTestCase;
use TheCrypticAce\Suitey\Steps;

abstract class TestCase extends BaseTestCase
{
    public function tearDown()
    {
        $this->cleanAllDatabases();

        parent::tearDown();
    }

    protected function relativeFixturePath()
    {
        return "../../../../tests/Fixture";
    }

    private function cleanAllDatabases()
    {
        $tables = [
            "default_tests", "bar_tests",
            "dup1_tests", "dup2_tests",
            "migrations",
        ];

        $databases = $this->app->make("db");

        foreach (["default", "foo", "bar"] as $connection) {
            $db = $databases->connection($connection);

            foreach ($tables as $table) {
                $db->statement("DROP TABLE IF EXISTS `{$table}`");
            }
        }
    }
}

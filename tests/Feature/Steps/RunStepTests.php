<?php

namespace Tests\Feature\Steps;

use TheCrypticAce\Suitey\Steps;

trait RunStepTests
{
    /**
     * @test
     * @dataProvider stepDataProvider
     **/
    public function run_step($steps, $status, $output, $database, $method)
    {
        foreach ($steps as $step) {
            if ($method === "config") {
                $this->app["config"]->push("suitey.steps", $step);
            } else if ($method === "add") {
                $this->suitey->add(new $step["class"]($step["options"]));
            }
        }

        $this->suitey->add(new Steps\RunCode("Asserting", function () use ($database) {
            foreach ($database as $assertion) {
                $this->assertDatabaseHas($assertion["table"], $assertion["attributes"], $assertion["connection"]);
            }
        }));

        $result = $this->artisan("test");
        $result->assertStatus($status);
        $result->assertOutputContains($output);
    }

    public function stepDataProvider()
    {
        foreach ($this->steps() as $name => $criteria) {
            yield "{$this->name()} via  ::add - {$name}" => [
                $criteria["steps"],
                $criteria["status"],
                $criteria["output"],
                $criteria["database"],
                "add",
            ];

            yield "{$this->name()} via config - {$name}" => [
                $criteria["steps"],
                $criteria["status"],
                $criteria["output"],
                $criteria["database"],
                "config",
            ];
        }
    }
}

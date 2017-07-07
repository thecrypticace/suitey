<?php

namespace Tests\Feature\Steps;

use TheCrypticAce\Suitey\Steps;

trait RunStepTests
{
    /**
     * @test
     * @dataProvider stepDataProvider
     **/
    public function run_step($steps, $status, $output, $database)
    {
        foreach ($steps as $step) {
            $this->suitey->add(new $step["class"]($step["options"]));
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
            yield "{$this->name()} - {$name}" => [
                $criteria["steps"],
                $criteria["status"],
                $criteria["output"],
                $criteria["database"],
            ];
        }
    }
}

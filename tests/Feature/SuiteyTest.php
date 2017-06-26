<?php

namespace Tests\Feature;

use Closure;
use Tests\TestCase;
use TheCrypticAce\Suitey\IO;
use TheCrypticAce\Suitey\Process;
use TheCrypticAce\Suitey\Steps\Step;
use TheCrypticAce\Suitey\Steps\RunCode;

class SuiteyTest extends TestCase
{
    /** @test */
    public function it_can_run_phpunit()
    {
        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertStepOutput([
            "[1/1] Run PHPUnit",
        ]);
    }

    /** @test */
    public function it_can_add_steps_to_run_before_phpunit()
    {
        $this->suitey->add($step = $this->successfulStep());

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertStepOutput([
            "[1/2] successful step",
            "[2/2] Run PHPUnit",
        ]);

        $this->assertTrue($step->hasRun);
    }

    /** @test */
    public function it_can_run_only_phpunit_if_requested()
    {
        $this->suitey->add($step = $this->successfulStep());

        $result = $this->artisan("test", [
            "--test-only" => true,
        ]);
        $result->assertStatus(0);
        $result->assertStepOutput([
            "[1/1] Run PHPUnit",
        ]);

        $this->assertFalse($step->hasRun);
    }

    /** @test */
    public function it_can_skip_non_phpunit_options()
    {
        $_SERVER["argv"] = ["artisan", "test", "--", "--filter", "stub"];

        $result = $this->artisan("test", [
            "--test-only" => true,
        ]);
        $result->assertStatus(0);
        $result->assertStepOutput([
            "[1/1] Run PHPUnit",
        ]);
    }

    /** @test */
    public function a_failing_step_prevents_further_steps_from_running()
    {
        $this->suitey->add($step1 = $this->failingStep());
        $this->suitey->add($step2 = $this->successfulStep());

        $result = $this->artisan("test");
        $result->assertStatus(1);
        $result->assertStepOutput([
            "[1/3] failing step",
        ]);

        $this->assertFalse($step2->hasRun);
    }

    /** @test */
    public function a_failing_process_prevents_further_steps_from_running()
    {
        $this->suitey->add($step1 = $this->failingProcessStep());
        $this->suitey->add($step2 = $this->successfulStep());

        $result = $this->artisan("test");
        $result->assertStatus(1);
        $result->assertStepOutput([
            "[1/3] failing process step",
        ]);

        $this->assertFalse($step2->hasRun);
    }

    /** @test */
    public function stub()
    {
        $this->assertTrue(true);
    }

    private function successfulStep()
    {
        return $this->step("successful step");
    }

    private function failingStep()
    {
        return $this->step("failing step", function () {
            return 1;
        });
    }

    private function failingProcessStep()
    {
        return $this->step("failing process step", function () {
            Process::binary("/usr/bin/idontexist")->run();
        });
    }

    private function step($name, Closure $code = null)
    {
        $code = $code ?? function () {};

        return new class ($name, $code) extends RunCode {
            public $hasRun = false;

            public function handle(IO $io, Closure $next)
            {
                $this->hasRun = true;

                return parent::handle($io, $next);
            }
        };
    }
}

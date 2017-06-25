<?php

namespace Tests\Feature;

use Closure;
use Tests\TestCase;
use TheCrypticAce\Suitey\IO;
use TheCrypticAce\Suitey\Steps\Step;

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
    public function stub()
    {
        $this->assertTrue(true);
    }

    private function successfulStep()
    {
        return $this->step("successful step");
    }

    private function step($name, Closure $code = null)
    {
        $code = $code ?? function () {};

        return new class ($name, $code) implements Step {
            public $hasRun = false;

            public function __construct($name, $code)
            {
                $this->name = $name;
                $this->code = $code;
            }

            public function name()
            {
                return $this->name;
            }

            public function handle(IO $io, Closure $next)
            {
                $this->hasRun = true;

                return ($this->code)($io, $next) ?? $next($io);
            }
        };
    }
}

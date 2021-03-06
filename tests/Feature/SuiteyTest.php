<?php

namespace Tests\Feature;

use Closure;
use Tests\TestCase;
use TheCrypticAce\Suitey\IO;
use TheCrypticAce\Suitey\Process;
use TheCrypticAce\Suitey\Steps;

class SuiteyTest extends TestCase
{
    /** @test */
    public function it_can_run_phpunit()
    {
        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertOutputContains([
            "[1/1] Run PHPUnit",
        ]);
    }

    /** @test */
    public function it_can_add_steps_to_run_before_phpunit()
    {
        $this->suitey->add($step = $this->successfulStep());

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertOutputContains([
            "[1/2] successful step",
            "[2/2] Run PHPUnit",
        ]);

        $this->assertTrue($step->hasRun);
    }

    /** @test */
    public function it_can_add_multiple_steps_in_one_call()
    {
        $this->suitey->add([
            $this->successfulStep(),
            $this->successfulStep(),
        ]);

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertOutputContains([
            "[1/3] successful step",
            "[2/3] successful step",
            "[3/3] Run PHPUnit",
        ]);
    }

    /** @test */
    public function it_can_add_multiple_steps_with_mixed_types_in_one_call()
    {
        $this->suitey->add([
            \Tests\Fixture\App\Steps\Stub::class,
            $this->successfulStep(),
            [
                "class" => \Tests\Fixture\App\Steps\Stub::class,
            ],
            $this->successfulStep(),
            [
                "class" => \Tests\Fixture\App\Steps\Stub::class,
                "options" => ["name" => "foo"],
            ],
        ]);

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertOutputContains([
            "[1/6] stub -",
            "[2/6] successful step",
            "[3/6] stub -",
            "[4/6] successful step",
            "[5/6] stub foo",
            "[6/6] Run PHPUnit",
        ]);
    }

    /** @test */
    public function it_can_run_only_phpunit_if_requested()
    {
        $this->suitey->add($step = $this->successfulStep());

        $result = $this->artisan("test", [
            "--test-only" => true,
        ]);
        $result->assertStatus(0);
        $result->assertOutputContains([
            "[1/1] Run PHPUnit",
        ]);

        $this->assertFalse($step->hasRun);
    }

    /** @test */
    public function it_can_run_only_phpunit_if_requested_when_configured_via_the_config()
    {
        $this->app["config"]->set("suitey.steps", [
            \Tests\Fixture\App\Steps\Stub::class,
        ]);

        $result = $this->artisan("test", [
            "--test-only" => true,
        ]);
        $result->assertStatus(0);
        $result->assertOutputContains([
            "[1/1] Run PHPUnit",
        ]);
    }

    /** @test */
    public function it_can_skip_non_phpunit_options()
    {
        $_SERVER["argv"] = ["artisan", "test", "--", "--filter", "stub"];

        $result = $this->artisan("test", [
            "--test-only" => true,
        ]);
        $result->assertStatus(0);
        $result->assertOutputContains([
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
        $result->assertOutputContains([
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
        $result->assertOutputContains([
            "[1/3] failing process step",
        ]);

        $this->assertFalse($step2->hasRun);
    }

    /** @test */
    public function you_may_add_steps_through_the_facade()
    {
        \TheCrypticAce\Suitey\Laravel\Suitey::add($this->successfulStep());

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertOutputContains([
            "[1/2] successful step",
            "[2/2] Run PHPUnit",
        ]);
    }

    /** @test */
    public function steps_can_be_specified_through_the_config_using_class_names()
    {
        $this->app["config"]->set("suitey.steps", [
            \Tests\Fixture\App\Steps\Stub::class,
        ]);

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertOutputContains([
            "[1/2] stub -",
            "[2/2] Run PHPUnit",
        ]);
    }

    /** @test */
    public function steps_can_be_specified_through_add_using_config_style()
    {
        $this->suitey->add([
            "class" => \Tests\Fixture\App\Steps\Stub::class,
        ]);

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertOutputContains([
            "[1/2] stub -",
            "[2/2] Run PHPUnit",
        ]);
    }

    /** @test */
    public function steps_can_be_specified_through_the_config_using_arrays()
    {
        $this->app["config"]->set("suitey.steps", [
            [
                "class" => \Tests\Fixture\App\Steps\Stub::class,
            ]
        ]);

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertOutputContains([
            "[1/2] stub -",
            "[2/2] Run PHPUnit",
        ]);
    }

    /** @test */
    public function steps_can_be_specified_through_the_config_using_arrays_to_configure_them()
    {
        $this->app["config"]->set("suitey.steps", [
            [
                "class" => \Tests\Fixture\App\Steps\Stub::class,
                "options" => [
                    "name" => "foo",
                ],
            ]
        ]);

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertOutputContains([
            "[1/2] stub foo",
            "[2/2] Run PHPUnit",
        ]);
    }

    /** @test */
    public function steps_specified_through_the_config_can_mix_and_match_strings_and_arrays()
    {
        $this->app["config"]->set("suitey.steps", [
            \Tests\Fixture\App\Steps\Stub::class,
            [
                "class" => \Tests\Fixture\App\Steps\Stub::class,
            ],
            [
                "class" => \Tests\Fixture\App\Steps\Stub::class,
                "options" => [
                    "name" => "foo",
                ],
            ],
        ]);

        $result = $this->artisan("test");
        $result->assertStatus(0);
        $result->assertOutputContains([
            "[1/4] stub -",
            "[2/4] stub -",
            "[3/4] stub foo",
            "[4/4] Run PHPUnit",
        ]);
    }

    /** @test */
    public function invalid_steps_specified_through_the_config_throw_an_error()
    {
        $this->app["config"]->set("suitey.steps", [
            false,
        ]);

        $result = $this->artisan("test");
        $result->assertStatus(1);
        $result->assertOutputContains([
            "[InvalidArgumentException]",
            "Invalid step configured at index 0 in [config/suitey.php]. Valid steps are class names or arrays wth 'class' and 'options' keys.",
       ]);
    }

    /** @test */
    public function invalid_steps_specified_through_the_config_at_another_index_throw_an_error()
    {
        $this->app["config"]->set("suitey.steps", [
            \Tests\Fixture\App\Steps\Stub::class,
            false,
        ]);

        $result = $this->artisan("test");
        $result->assertStatus(1);
        $result->assertOutputContains([
            "[InvalidArgumentException]",
            "Invalid step configured at index 1 in [config/suitey.php]. Valid steps are class names or arrays wth 'class' and 'options' keys.",
       ]);
    }

    /** @test */
    public function errors_thrown_during_steps_are_caught_and_returned()
    {
        $this->suitey->add(new Steps\RunCode("Throwing error", function () {
            echo $foo;
        }));

        $result = $this->artisan("test");
        $result->assertStatus(1);
        $result->assertOutputContains([
            "[1/2] Throwing error",
        ]);
    }

    /** @test */
    public function exceptions_thrown_during_steps_are_caught_and_returned()
    {
        $this->suitey->add(new Steps\RunCode("Throwing exception", function () {
            throw new \Exception("test");
        }));

        $result = $this->artisan("test");
        $result->assertStatus(1);
        $result->assertOutputContains([
            "[1/2] Throwing exception",
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

        return new class ($name, $code) extends Steps\RunCode {
            public $hasRun = false;

            public function handle(IO $io, Closure $next)
            {
                $this->hasRun = true;

                return parent::handle($io, $next);
            }
        };
    }
}

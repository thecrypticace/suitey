<?php

namespace Tests\Feature;

use Tests\TestCase;
use TheCrypticAce\Suitey\Environment\Environment;

class EnvironmentTest extends TestCase
{
    /** @test */
    public function it_can_load_the_environment()
    {
        $this->assertNull(env("env_dot_testing"));
        $this->assertNull(env("env_phpunit_xml"));
        $this->assertNull(env("env_phpunit_xml_dist"));

        Environment::loadUsing($this->app);

        $this->assertEquals("1", env("env_dot_testing"));
        $this->assertEquals("1", env("env_phpunit_xml"));
        $this->assertEquals("1", env("env_phpunit_xml_dist"));
    }
}

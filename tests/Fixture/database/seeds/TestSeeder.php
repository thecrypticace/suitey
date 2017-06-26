<?php

use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    public function run()
    {
        DB::table("default_tests")->insert(["id" => 2]);
    }
}

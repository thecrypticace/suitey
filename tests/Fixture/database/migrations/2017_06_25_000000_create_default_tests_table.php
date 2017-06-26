<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefaultTestsTable extends Migration
{
    public function up()
    {
        Schema::create("default_tests", function (Blueprint $table) {
            $table->increments("id");
        });

        \DB::table("default_tests")->insert(["id" => 1]);
    }

    public function down()
    {
        Schema::dropIfExists("default_tests");
    }
}

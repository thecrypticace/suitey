<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDupTestsTable extends Migration
{
    public function up()
    {
        Schema::create("dup1_tests", function (Blueprint $table) {
            $table->increments("id");
        });

        \DB::table("dup1_tests")->insert(["id" => 1]);
    }

    public function down()
    {
        Schema::dropIfExists("dup1_tests");
    }
}

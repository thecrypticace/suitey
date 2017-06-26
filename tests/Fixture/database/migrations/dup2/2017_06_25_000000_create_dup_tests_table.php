<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDupTestsTable extends Migration
{
    public function up()
    {
        Schema::create("dup2_tests", function (Blueprint $table) {
            $table->increments("id");
        });

        \DB::table("dup2_tests")->insert(["id" => 1]);
    }

    public function down()
    {
        Schema::dropIfExists("dup2_tests");
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarTestsTable extends Migration
{
    public function up()
    {
        Schema::create("bar_tests", function (Blueprint $table) {
            $table->increments("id");
        });

        \DB::table("bar_tests")->insert(["id" => 1]);
    }

    public function down()
    {
        Schema::dropIfExists("bar_tests");
    }
}

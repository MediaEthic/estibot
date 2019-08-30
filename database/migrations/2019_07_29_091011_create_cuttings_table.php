<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuttingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuttings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableTimestamps();
            $table->string('name', 200);
            $table->unsignedInteger('dimension_width');
            $table->unsignedInteger('dimension_length');
            $table->unsignedInteger('bleed_width');
            $table->unsignedInteger('bleed_length');
            $table->unsignedInteger('pose_width');
            $table->unsignedInteger('pose_length');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuttings');
    }
}

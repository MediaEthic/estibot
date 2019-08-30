<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsumablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableTimestamps();
            $table->string('name', 200);
            $table->unsignedSmallInteger('weight')->nullable()->default(null);
            $table->float('thickness', 10, 6)->nullable()->default(null);
            $table->unsignedInteger('width');
            $table->unsignedInteger('length');
            $table->float('price', 10, 6);
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consumables');
    }
}

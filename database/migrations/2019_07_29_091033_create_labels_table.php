<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableTimestamps();
            $table->enum('third_type', ['ethic', 'estibot'])->default('estibot');
            $table->unsignedBigInteger('third_id')->nullable()->default(null);
            $table->string('name', 200)->nullable()->default(null);
            $table->unsignedInteger('width');
            $table->unsignedInteger('length');
            $table->string('printing_id', 5)->nullable()->default(null);
            $table->unsignedTinyInteger('number_colors')->nullable()->default(null);
            $table->boolean('quadri')->default(false);
            $table->enum('substrate_type', ['ethic', 'estibot'])->default('estibot');
            $table->unsignedBigInteger('substrate_id')->nullable()->default(null);
            $table->enum('cutting_type', ['ethic', 'estibot'])->default('estibot');
            $table->unsignedBigInteger('cutting_id')->nullable()->default(null);
            $table->enum('winding', ['ihead', 'ifoot', 'iright', 'ileft', 'ehead', 'efoot', 'eright', 'eleft'])->default('ihead');
            $table->unsignedInteger('packing')->nullable()->default(null);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('labels');
    }
}

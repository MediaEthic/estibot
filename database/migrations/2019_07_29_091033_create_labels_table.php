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
            $table->string('name', 200)->nullable()->default(null);
            $table->unsignedInteger('width');
            $table->unsignedInteger('length');
            $table->unsignedBigInteger('printing_id')->nullable()->default(null);
            $table->unsignedTinyInteger('number_colors')->nullable()->default(null);
            $table->boolean('quadri')->default(false);
            $table->enum('substrate_type', ['ethic', 'estibot'])->default('estibot');
            $table->unsignedBigInteger('substrate_id')->nullable()->default(null);
            $table->enum('cutting_type', ['ethic', 'estibot'])->default('estibot');
            $table->unsignedBigInteger('cutting_id')->nullable()->default(null);
            $table->enum('winding', ['ihead', 'ifoot', 'iright', 'ileft', 'ehead', 'efoot', 'eright', 'eleft'])->default('ihead');
            $table->unsignedInteger('packing')->nullable()->default(null);

            $table->foreign('substrate_id')
                ->references('id')
                ->on('substrates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('labels', function (Blueprint $table) {
            $table->dropForeign(['substrate_id']);
        });

        Schema::dropIfExists('labels');
    }
}

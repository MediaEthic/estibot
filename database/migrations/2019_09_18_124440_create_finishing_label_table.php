<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinishingLabelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finishing_label', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableTimestamps();
            $table->unsignedBigInteger('finishing_id')->nullable()->default(null);
            $table->foreign('finishing_id')
                ->references('id')
                ->on('finishings')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('label_id')->nullable()->default(null);
            $table->foreign('label_id')
                ->references('id')
                ->on('labels')
                ->onDelete('cascade')
                ->onUpdate('cascade');


            $table->float('shape', 10, 6)->nullable()->default(null);
            $table->boolean('reworking')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('finishing_label');
    }
}

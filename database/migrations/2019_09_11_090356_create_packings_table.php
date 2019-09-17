<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name', 200);
            $table->float('makeready_times', 10, 6)->nullable()->default(null);
            $table->enum('unit_cadence', ['striking', 'linear'])->default('striking');
            $table->unsignedInteger('cadence')->nullable()->default(null);
            $table->float('duration', 10, 6)->nullable()->default(null);
            $table->float('hourly_rate', 10, 6)->nullable()->default(null);
            $table->tinyInteger('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packings');
    }
}

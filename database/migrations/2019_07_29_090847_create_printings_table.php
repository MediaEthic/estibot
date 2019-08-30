<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrintingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('printings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableTimestamps();
            $table->string('maker', 50);
            $table->string('name', 200);
            $table->unsignedTinyInteger('number_units')->nullable()->default(null);
            $table->unsignedTinyInteger('number_colors')->nullable()->default(null);
            $table->unsignedInteger('size_paperminx')->nullable()->default(null);
            $table->unsignedInteger('size_paperminy')->nullable()->default(null);
            $table->unsignedInteger('size_papermaxx')->nullable()->default(null);
            $table->unsignedInteger('size_papermaxy')->nullable()->default(null);
            $table->unsignedInteger('printable_areax')->nullable()->default(null);
            $table->unsignedInteger('printable_areay')->nullable()->default(null);
            $table->unsignedSmallInteger('weight_minimum')->nullable()->default(null);
            $table->unsignedSmallInteger('weight_maximum')->nullable()->default(null);
            $table->double('thickness_minimum')->nullable()->default(null);
            $table->double('thickness_maximum')->nullable()->default(null);
            $table->double('makeready_times')->nullable()->default(null);
            $table->unsignedInteger('cadence')->nullable()->default(null);
            $table->double('hourly_rate')->nullable()->default(null);
            $table->unsignedInteger('overlay_sheet')->nullable()->default(null);
            $table->double('wastage')->nullable()->default(null);
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
        Schema::dropIfExists('printings');
    }
}

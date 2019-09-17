<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinishingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finishings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableTimestamps();
            $table->unsignedBigInteger('printing_id');
            $table->string('name', 200);
            $table->boolean('consumable')->default(false);
            $table->float('makeready_times', 10, 6)->nullable()->default(null);
            $table->unsignedInteger('cadence')->nullable()->default(null);
            $table->unsignedInteger('overlay_sheet')->nullable()->default(null);

//            $table->foreign('printing_id')
//                ->references('id')
//                ->on('printings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('finishings', function (Blueprint $table) {
//            $table->dropForeign(['printing_id']);
//        });

        Schema::dropIfExists('finishings');
    }
}

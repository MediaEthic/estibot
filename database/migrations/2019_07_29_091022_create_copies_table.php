<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCopiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('label_id');
            $table->unsignedBigInteger('quantity');
            $table->unsignedTinyInteger('models');
            $table->unsignedTinyInteger('plates');
            $table->float('price', 10, 6);
            $table->float('shipping', 10, 6)->nullable()->default(null);
            $table->float('vat', 10, 6);
//            $table->json('operations')->nullable()->default(null);

//            $table->foreign('label_id')
//                ->references('id')
//                ->on('labels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('copies', function (Blueprint $table) {
//            $table->dropForeign(['label_id']);
//        });

        Schema::dropIfExists('copies');
    }
}

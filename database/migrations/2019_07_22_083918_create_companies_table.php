<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name', 38);
            $table->string('logo', 200)->nullable()->default(null);
            $table->string('phone', 35)->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->string('address_line1', 38)->nullable()->default(null);
            $table->string('address_line2', 38)->nullable()->default(null);
            $table->string('address_line3', 38)->nullable()->default(null);
            $table->string('zipcode', 38)->nullable()->default(null);
            $table->string('city', 38)->nullable()->default(null);
            $table->unsignedInteger('country_id')->nullable()->default(75);
//            $table->foreign('country_id')
//                ->references('id')
//                ->on('countries')
//                ->onDelete('restrict')
//                ->onUpdate('restrict');
            $table->unsignedInteger('language_id')->nullable()->default(47);
//            $table->foreign('language_id')
//                ->references('id')
//                ->on('languages')
//                ->onDelete('restrict')
//                ->onUpdate('restrict');
            $table->unsignedInteger('time_zone_id')->nullable()->default(40);
//            $table->foreign('time_zone_id')
//                ->references('id')
//                ->on('time_zones')
//                ->onDelete('restrict')
//                ->onUpdate('restrict');
            $table->string('legal_form', 45)->nullable()->default(null);
            $table->string('capital', 45)->nullable()->default(null);
            $table->string('register', 45)->nullable()->default(null);
            $table->string('siret', 45)->nullable()->default(null);
            $table->string('tva', 45)->nullable()->default(null);
            $table->unsignedTinyInteger('settlement_id')->nullable()->default('1');
            $table->unsignedInteger('duration_number')->nullable()->default(1);
            $table->enum('duration_format', ['month', 'day'])->default('month');
            $table->text('head_quotation')->nullable()->default(null);
            $table->text('foot_quotation')->nullable()->default(null);
            $table->string('signature_quotation', 200)->nullable()->default(null);
            $table->text('subject_email')->nullable()->default(null);
            $table->text('body_email')->nullable()->default(null);
            $table->string('twitter', 200)->nullable()->default(null);
            $table->string('facebook', 200)->nullable()->default(null);
            $table->string('gplus', 200)->nullable()->default(null);
            $table->string('linkedin', 200)->nullable()->default(null);
            $table->string('instagram', 200)->nullable()->default(null);
            $table->string('dribble', 200)->nullable()->default(null);
            $table->string('youtube', 200)->nullable()->default(null);
            $table->string('vimeo', 200)->nullable()->default(null);
            $table->string('github', 200)->nullable()->default(null);
            $table->string('blog', 200)->nullable()->default(null);
            $table->string('prepress', 5)->nullable()->default(null);
            $table->string('winder', 5)->nullable()->default(null);
            $table->string('api_url', 35)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
//            $table->dropForeign(['country_id']);
//            $table->dropForeign(['language_id']);
//            $table->dropForeign(['time_zone_id']);
        });

        Schema::dropIfExists('companies');
    }
}

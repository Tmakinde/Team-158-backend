<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmers', function (Blueprint $table) {
           // $table->dropColumn('invests_id');
           // $table->longtext('api_token');
            $table->bigInteger('investors_id')->unsigned();
            $table->foreign('investors_id')->references('id')->on('investors')->onDelete('cascade')->onUpdate('cascade')->nullable();
        });

        Schema::table('investors', function (Blueprint $table) {
            $table->longtext('api_token')->default('empty');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('column');
    }
}

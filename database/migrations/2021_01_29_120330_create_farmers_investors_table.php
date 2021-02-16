<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmersInvestorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmer_investor', function (Blueprint $table) {
            $table->bigInteger("farmer_id")->unsigned();
            $table->bigInteger("investor_id")->unsigned();
            $table->foreign("farmer_id")->references('id')->on('farmers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign("investor_id")->references('id')->on('investors')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farmers_investors');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpGatewaySetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_gateway_setting', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gateway_id')->comment = "References MercadoPago Gateway Table";
            $table->string('mp_client');
            $table->string('mp_secret');
            $table->string('description')->nullable();
            $table->string('type')->default('TEXT');
            $table->boolean('is_hidden')->default(0);
            $table->text('dataset')->nullable()->comment = 'Serialised Data set';
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
        Schema::dropIfExists('mp_gateway_setting');
    }
}

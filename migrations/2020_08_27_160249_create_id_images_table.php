<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('id_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('broker_detail_id');
            $table->unsignedBigInteger('images_id');
            $table->timestamps();

            $table->foreign('broker_detail_id')->references('id')->on('broker_details');
            $table->foreign('images_id')->references('id')->on('images');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('id_images');
    }
}

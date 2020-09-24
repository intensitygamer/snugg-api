<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broker_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('subscription_id');
            $table->enum('id_status', ['pending', 'approved','rejected']);
            $table->bigInteger('prc_id')->nullable();
            $table->date('expiration_date')->nullable();
            $table->timestamps();

            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('subscription_id')->references('id')->on('subscription_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('broker_details');
    }
}

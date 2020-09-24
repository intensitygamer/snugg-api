<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
             
            $table->id('listing_id');              
            $table->integer('user_id');              
            $table->timestamps();
            $table->enum('property_status', ['Leased', 'Rent', 'Saled', 'Sold']);    
            $table->string('listing_name');              
            $table->string('latitude');         
            $table->string('longitude');         
            $table->integer('bedroom');         
            $table->integer('bathroom');         
            $table->integer('garage');         
            $table->float('floor_area');         
            $table->float('lot_area');         
            $table->enum('listing_status', ['pending', 'approved', 'published', 'deleted']);    
            $table->timestamp('date_approved')->nullable();         
            $table->integer('approved_by_id');         

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listings');
    }
}

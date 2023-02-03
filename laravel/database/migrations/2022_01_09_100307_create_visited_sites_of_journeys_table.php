<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitedSitesOfJourneysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visited_sites_of_journeys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');          
            $table->integer('userjourney_id');            
            $table->integer('site_id');  
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
        Schema::dropIfExists('visited_sites_of_journeys');
    }
}

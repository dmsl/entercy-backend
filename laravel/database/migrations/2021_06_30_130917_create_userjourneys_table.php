<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserjourneysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userjourneys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('status')->nullable();
            $table->string('journey_ids')->nullable();
            $table->string('districts')->nullable();
            $table->string('arriving_date')->nullable();
            $table->string('departing_date')->nullable();
            $table->string('travel_types')->nullable();
            $table->string('categories')->nullable();
            $table->string('transportation')->nullable();
            $table->string('suggest_recommend')->nullable();
            $table->text('path_thumbnail', 500)->nullable();
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
        Schema::dropIfExists('userjourneys');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorytellingratesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storytellingrates', function (Blueprint $table) {
            $table->bigIncrements('id');            
            $table->integer('rate_score');  
            $table->integer('storytelling_id');
            $table->integer('user_id');
            $table->text('comments', 1000)->nullable();            
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
        Schema::dropIfExists('storytellingrates');
    }
}

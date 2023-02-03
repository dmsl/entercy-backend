<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePossibleanswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('possibleanswers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('question_id');
            $table->text('answer_en', 5000);            
            $table->text('answer_gr', 5000)->nullable();
            $table->text('answer_ru', 5000)->nullable();
            $table->text('answer_it', 5000)->nullable();
            $table->text('answer_fr', 5000)->nullable();
            $table->text('answer_ge', 5000)->nullable();
            $table->integer('order_num')->nullable();
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
        Schema::dropIfExists('possibleanswers');
    }
}

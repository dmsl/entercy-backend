<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('question_en', 5000);            
            $table->text('question_gr', 5000)->nullable();
            $table->text('question_ru', 5000)->nullable();
            $table->text('question_it', 5000)->nullable();
            $table->text('question_fr', 5000)->nullable();
            $table->text('question_ge', 5000)->nullable();
            $table->string('type')->nullable();
            $table->string('compulsory')->nullable();
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
        Schema::dropIfExists('questions');
    }
}

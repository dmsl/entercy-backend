<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThematicroutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thematicroutes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name', 500);
            $table->text('name_gr', 500)->nullable();
            $table->text('name_ru', 500)->nullable();
            $table->text('name_it', 500)->nullable();
            $table->text('name_fr', 500)->nullable();
            $table->text('name_ge', 500)->nullable();
            $table->text('description', 5000)->nullable();            
            $table->text('description_gr', 5000)->nullable();
            $table->text('description_ru', 5000)->nullable();
            $table->text('description_it', 5000)->nullable();
            $table->text('description_fr', 5000)->nullable();
            $table->text('description_ge', 5000)->nullable();
            $table->text('path_img', 500)->nullable();
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
        Schema::dropIfExists('thematicroutes');
    }
}

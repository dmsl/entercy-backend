<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicecategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicecategories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('name_gr')->nullable();
            $table->string('name_ru')->nullable();
            $table->string('name_it')->nullable();
            $table->string('name_fr')->nullable();
            $table->string('name_ge')->nullable();
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
        Schema::dropIfExists('servicecategories');
    }
}

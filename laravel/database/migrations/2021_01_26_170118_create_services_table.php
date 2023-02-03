<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
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
            $table->text('path_img1', 500)->nullable();
            $table->text('path_img2', 500)->nullable();
            $table->text('path_img3', 500)->nullable();
            $table->text('path_logo', 500)->nullable();
            $table->text('license', 500)->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->text('address', 1000)->nullable();
            $table->string('coord_lat')->nullable();
            $table->string('coord_long')->nullable();
            $table->text('website', 500)->nullable();
            $table->integer('servicecategory_id')->nullable();
            $table->string('other_servicecategory')->nullable();
            $table->string('monday_start')->nullable();
            $table->string('monday_end')->nullable();
            $table->string('tuesday_start')->nullable();
            $table->string('tuesday_end')->nullable();
            $table->string('wednesday_start')->nullable();
            $table->string('wednesday_end')->nullable();
            $table->string('thursday_start')->nullable();
            $table->string('thursday_end')->nullable();
            $table->string('friday_start')->nullable();
            $table->string('friday_end')->nullable();
            $table->string('saturday_start')->nullable();
            $table->string('saturday_end')->nullable();
            $table->string('sunday_start')->nullable();
            $table->string('sunday_end')->nullable();
            $table->string('month_closed_from')->nullable();
            $table->string('month_closed_to')->nullable();
            $table->string('premises')->nullable();
            $table->string('cuisine_type1')->nullable();
            $table->string('cuisine_type2')->nullable();
            $table->string('cuisine_type3')->nullable();
            $table->string('cuisine_type4')->nullable();
            $table->string('cuisine_type5')->nullable();
            $table->string('other_cuisine_type')->nullable();
            $table->string('dietary_restr1')->nullable();
            $table->string('dietary_restr2')->nullable();
            $table->string('dietary_restr3')->nullable();
            $table->string('price')->nullable();
            $table->string('hotel_class')->nullable();
            $table->string('enabled')->nullable();
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
        Schema::dropIfExists('services');
    }
}

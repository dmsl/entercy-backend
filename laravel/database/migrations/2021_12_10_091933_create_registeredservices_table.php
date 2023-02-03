<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisteredservicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registeredservices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('folder_number')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();   
            $table->string('city')->nullable();         
            $table->string('mountain_resort')->nullable();
            $table->string('telephone')->nullable();            
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('category')->nullable();
            $table->string('class')->nullable();
            $table->string('business_company')->nullable();
            $table->string('director')->nullable();            
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
        Schema::dropIfExists('registeredservices');
    }
}

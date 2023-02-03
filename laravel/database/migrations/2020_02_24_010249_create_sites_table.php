<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
          $table->bigIncrements('id');
         $table->string('name');
         $table->string('district');
         $table->string('town');
         $table->string('category');
         $table->float('fee', 6, 2);
         $table->string('contact_tel');
         $table->string('url');
         $table->integer('main_poi');
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
        Schema::dropIfExists('sites');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutdoorLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outdoor_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->integer('outdoor_group_id');
            $table->integer('storytelling_id')->nullable();
            $table->integer('poi_media_id')->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->float('x_scale')->nullable();
            $table->float('y_scale')->nullable();
            $table->float('z_scale')->nullable();
            $table->float('x_rotation')->nullable();
            $table->float('y_rotation')->nullable();
            $table->float('z_rotation')->nullable();
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
        Schema::dropIfExists('outdoor_links');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQrRoomLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qr_room_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('qr_room_id');
            $table->string('name')->nullable();
            $table->integer('poi_media_id');
            $table->float('x_position')->nullable();
            $table->float('y_position')->nullable();
            $table->float('z_position')->nullable();
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
        Schema::dropIfExists('qr_room_links');
    }
}

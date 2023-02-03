<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_trackings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->unsignedBigInteger('object_type');
            $table->unsignedBigInteger('event_type');
            $table->integer('object_id');
            $table->text('metadata')->nullable();
            $table->timestamps();

            $table->foreign('object_type')->references('id')->on('object_types')->onDelete('cascade');
            $table->foreign('event_type')->references('id')->on('event_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_trackings');
    }
}

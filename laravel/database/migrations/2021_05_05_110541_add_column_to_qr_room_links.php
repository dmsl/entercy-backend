<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToQrRoomLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qr_room_links', function (Blueprint $table) {
            $table->integer('storytelling_id')->nullable();
            $table->integer('poi_media_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qr_room_links', function (Blueprint $table) {
            $table->dropColumn('storytelling_id');
        });
    }
}

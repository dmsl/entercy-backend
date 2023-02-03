<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToQrRoomGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qr_room_groups', function (Blueprint $table) {
            $table->float('x_position')->nullable();
            $table->float('y_position')->nullable();
            $table->float('z_position')->nullable();
            $table->float('x_scale')->nullable();
            $table->float('y_scale')->nullable();
            $table->float('z_scale')->nullable();
            $table->float('x_rotation')->nullable();
            $table->float('y_rotation')->nullable();
            $table->float('z_rotation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qr_room_groups', function (Blueprint $table) {
            $table->dropColumn('x_position');
            $table->dropColumn('y_position');
            $table->dropColumn('z_position');
            $table->dropColumn('x_scale');
            $table->dropColumn('y_scale');
            $table->dropColumn('z_scale');
            $table->dropColumn('x_rotation');
            $table->dropColumn('y_rotation');
            $table->dropColumn('z_rotation');

        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAllXyzColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qr_room_groups', function (Blueprint $table) {
            $table->float('x_position', 8, 6)->change();
            $table->float('y_position', 8, 6)->change();
            $table->float('z_position', 8, 6)->change();
            $table->float('x_scale', 8, 6)->change();
            $table->float('y_scale', 8, 6)->change();
            $table->float('z_scale', 8, 6)->change();
            $table->float('x_rotation', 8, 6)->change();
            $table->float('y_rotation', 8, 6)->change(); 
            $table->float('z_rotation', 8, 6)->change(); 
        });

        Schema::table('qr_room_links', function (Blueprint $table) {
            $table->float('x_position', 8, 6)->change();
            $table->float('y_position', 8, 6)->change();
            $table->float('z_position', 8, 6)->change();
            $table->float('x_scale', 8, 6)->change();
            $table->float('y_scale', 8, 6)->change();
            $table->float('z_scale', 8, 6)->change();
            $table->float('x_rotation', 8, 6)->change();
            $table->float('y_rotation', 8, 6)->change(); 
            $table->float('z_rotation', 8, 6)->change(); 
        });

        Schema::table('outdoor_groups', function (Blueprint $table) {
            $table->float('latitude', 8, 6)->change();
            $table->float('longitude', 8, 6)->change();
            $table->float('altitude', 8, 6)->change(); 
        });

        Schema::table('outdoor_links', function (Blueprint $table) {
            $table->float('latitude', 8, 6)->change();
            $table->float('longitude', 8, 6)->change();
            $table->float('x_scale', 8, 6)->change();
            $table->float('y_scale', 8, 6)->change();
            $table->float('z_scale', 8, 6)->change();
            $table->float('x_rotation', 8, 6)->change();
            $table->float('y_rotation', 8, 6)->change(); 
            $table->float('z_rotation', 8, 6)->change();  
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

        Schema::table('qr_room_links', function (Blueprint $table) {           
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

        Schema::table('outdoor_groups', function (Blueprint $table) {
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->float('altitude')->nullable();
        });

        Schema::table('outdoor_links', function (Blueprint $table) {           
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->float('x_scale')->nullable();
            $table->float('y_scale')->nullable();
            $table->float('z_scale')->nullable();
            $table->float('x_rotation')->nullable();
            $table->float('y_rotation')->nullable();
            $table->float('z_rotation')->nullable();
        });




    }
}

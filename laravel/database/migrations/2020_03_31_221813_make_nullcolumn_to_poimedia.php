<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNullcolumnToPoimedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('poimedia', function (Blueprint $table) {
          $table->string('uri')->nullable()->change();
          $table->string('uri', 5000)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('poimedia', function (Blueprint $table) {
          $table->string('uri')->nullable()->change();
          $table->string('uri', 191)->change(); //default
        });
    }
}

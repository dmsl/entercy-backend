<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNullcolumnsToSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {

          $table->string('name')->nullable()->change();
          $table->string('district')->nullable()->change();
          $table->string('town')->nullable()->change();
          $table->string('category')->nullable()->change();
          $table->float('fee', 6, 2)->nullable()->change();
          $table->string('contact_tel')->nullable()->change();
          $table->string('url')->nullable()->change();
          $table->integer('main_poi')->nullable()->change();
          $table->string('path')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
          $table->string('name')->nullable()->change();
          $table->string('district')->nullable()->change();
          $table->string('town')->nullable()->change();
          $table->string('category')->nullable()->change();
          $table->float('fee', 6, 2)->nullable()->change();
          $table->string('contact_tel')->nullable()->change();
          $table->string('url')->nullable()->change();
          $table->integer('main_poi')->nullable()->change();
          $table->string('path')->nullable()->change();
        });
    }
}

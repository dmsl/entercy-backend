<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNullcolumnsToPois extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pois', function (Blueprint $table) {
          $table->string('name')->nullable()->change();
          $table->string('description')->nullable()->change();
          $table->string('year')->nullable()->change();
          $table->string('coordinates')->nullable()->change();
          $table->string('toponym')->nullable()->change();
          $table->integer('site_id')->nullable()->change();
          $table->integer('parent_poi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pois', function (Blueprint $table) {
          $table->string('name')->nullable()->change();
          $table->string('description')->nullable()->change();
          $table->string('year')->nullable()->change();
          $table->string('coordinates')->nullable()->change();
          $table->string('toponym')->nullable()->change();
          $table->integer('site_id')->nullable()->change();
          $table->integer('parent_poi')->nullable()->change();
        });
    }
}

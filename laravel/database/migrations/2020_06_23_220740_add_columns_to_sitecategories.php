<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSitecategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sitecategories', function (Blueprint $table) {
            $table->string('path_video')->nullable();
            $table->string('description', 5000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sitecategories', function (Blueprint $table) {
            $table->dropColumn('path_video');
            $table->dropColumn('description');
        });
    }
}

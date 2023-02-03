<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditColumnsUserpreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('userpreferences', function (Blueprint $table) {
            $table->dropColumn('json_data');
            $table->string('districts')->nullable();
            $table->string('arriving_date')->nullable();
            $table->string('departing_date')->nullable();
            $table->string('travel_types')->nullable();
            $table->string('categories')->nullable();
            $table->string('transportation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('userpreferences', function (Blueprint $table) {
            $table->string('json_data');
            $table->dropColumn('districts');
            $table->dropColumn('arriving_date');
            $table->dropColumn('departing_date');
            $table->dropColumn('travel_types');
            $table->dropColumn('categories');
            $table->dropColumn('transportation');
        });
    }
}

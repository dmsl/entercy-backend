<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTranslationColumnsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accessibilities', function (Blueprint $table) {            
            $table->text('name_gr')->nullable();
            $table->text('name_ru')->nullable();
            $table->text('name_it')->nullable();
            $table->text('name_fr')->nullable();
            $table->text('name_ge')->nullable();
        });

        Schema::table('app_contents', function (Blueprint $table) {            
            $table->text('name_gr', 500)->nullable();
            $table->text('name_ru', 500)->nullable();
            $table->text('name_it', 500)->nullable();
            $table->text('name_fr', 500)->nullable();
            $table->text('name_ge', 500)->nullable();
            $table->text('description_gr', 5000)->nullable();
            $table->text('description_ru', 5000)->nullable();
            $table->text('description_it', 5000)->nullable();
            $table->text('description_fr', 5000)->nullable();
            $table->text('description_ge', 5000)->nullable();
        });

        Schema::table('chronologicals', function (Blueprint $table) {            
            $table->text('name_gr', 500)->nullable();
            $table->text('name_ru', 500)->nullable();
            $table->text('name_it', 500)->nullable();
            $table->text('name_fr', 500)->nullable();
            $table->text('name_ge', 500)->nullable();
            $table->text('description_gr', 5000)->nullable();
            $table->text('description_ru', 5000)->nullable();
            $table->text('description_it', 5000)->nullable();
            $table->text('description_fr', 5000)->nullable();
            $table->text('description_ge', 5000)->nullable();
        });

         Schema::table('cy_districts', function (Blueprint $table) {            
            $table->text('name_gr')->nullable();
            $table->text('name_ru')->nullable();
            $table->text('name_it')->nullable();
            $table->text('name_fr')->nullable();
            $table->text('name_ge')->nullable();
            $table->text('description_gr', 5000)->nullable();
            $table->text('description_ru', 5000)->nullable();
            $table->text('description_it', 5000)->nullable();
            $table->text('description_fr', 5000)->nullable();
            $table->text('description_ge', 5000)->nullable();
        });

         Schema::table('pois', function (Blueprint $table) {            
            $table->text('name_gr')->nullable();
            $table->text('name_ru')->nullable();
            $table->text('name_it')->nullable();
            $table->text('name_fr')->nullable();
            $table->text('name_ge')->nullable();
            $table->text('description', 5000)->change();
            $table->text('description_gr', 5000)->nullable();
            $table->text('description_ru', 5000)->nullable();
            $table->text('description_it', 5000)->nullable();
            $table->text('description_fr', 5000)->nullable();
            $table->text('description_ge', 5000)->nullable();
            $table->text('toponym_gr')->nullable();
            $table->text('toponym_ru')->nullable();
            $table->text('toponym_it')->nullable();
            $table->text('toponym_fr')->nullable();
            $table->text('toponym_ge')->nullable();
        });

          Schema::table('sitecategories', function (Blueprint $table) {            
            $table->text('name_gr')->nullable();
            $table->text('name_ru')->nullable();
            $table->text('name_it')->nullable();
            $table->text('name_fr')->nullable();
            $table->text('name_ge')->nullable();
            $table->text('description_gr', 5000)->nullable();
            $table->text('description_ru', 5000)->nullable();
            $table->text('description_it', 5000)->nullable();
            $table->text('description_fr', 5000)->nullable();
            $table->text('description_ge', 5000)->nullable();
        });

          Schema::table('sites', function (Blueprint $table) {            
            $table->text('name_gr')->nullable();
            $table->text('name_ru')->nullable();
            $table->text('name_it')->nullable();
            $table->text('name_fr')->nullable();
            $table->text('name_ge')->nullable();
            $table->text('town_gr')->nullable();
            $table->text('town_ru')->nullable();
            $table->text('town_it')->nullable();
            $table->text('town_fr')->nullable();
            $table->text('town_ge')->nullable();
        });

          Schema::table('siteworkinghours', function (Blueprint $table) {            
            $table->text('day_gr')->nullable();
            $table->text('day_ru')->nullable();
            $table->text('day_it')->nullable();
            $table->text('day_fr')->nullable();
            $table->text('day_ge')->nullable();
        });

          Schema::table('transportations', function (Blueprint $table) {            
            $table->text('name_gr')->nullable();
            $table->text('name_ru')->nullable();
            $table->text('name_it')->nullable();
            $table->text('name_fr')->nullable();
            $table->text('name_ge')->nullable();
        });

          Schema::table('traveltypes', function (Blueprint $table) {            
            $table->text('name_gr')->nullable();
            $table->text('name_ru')->nullable();
            $table->text('name_it')->nullable();
            $table->text('name_fr')->nullable();
            $table->text('name_ge')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accessibilities', function (Blueprint $table) {
            $table->dropColumn('name_gr');
            $table->dropColumn('name_ru');
            $table->dropColumn('name_it');
            $table->dropColumn('name_fr');
            $table->dropColumn('name_ge');
        });

        Schema::table('app_contents', function (Blueprint $table) {
            $table->dropColumn('name_gr');
            $table->dropColumn('name_ru');
            $table->dropColumn('name_it');
            $table->dropColumn('name_fr');
            $table->dropColumn('name_ge');
            $table->dropColumn('description_gr');
            $table->dropColumn('description_ru');
            $table->dropColumn('description_it');
            $table->dropColumn('description_fr');
            $table->dropColumn('description_ge');
        });

        Schema::table('chronologicals', function (Blueprint $table) {
            $table->dropColumn('name_gr');
            $table->dropColumn('name_ru');
            $table->dropColumn('name_it');
            $table->dropColumn('name_fr');
            $table->dropColumn('name_ge');
            $table->dropColumn('description_gr');
            $table->dropColumn('description_ru');
            $table->dropColumn('description_it');
            $table->dropColumn('description_fr');
            $table->dropColumn('description_ge');
        });

        Schema::table('cy_districts', function (Blueprint $table) {
            $table->dropColumn('name_gr');
            $table->dropColumn('name_ru');
            $table->dropColumn('name_it');
            $table->dropColumn('name_fr');
            $table->dropColumn('name_ge');
            $table->dropColumn('description_gr');
            $table->dropColumn('description_ru');
            $table->dropColumn('description_it');
            $table->dropColumn('description_fr');
            $table->dropColumn('description_ge');
        });

        Schema::table('pois', function (Blueprint $table) {
            $table->dropColumn('name_gr');
            $table->dropColumn('name_ru');
            $table->dropColumn('name_it');
            $table->dropColumn('name_fr');
            $table->dropColumn('name_ge');
            $table->dropColumn('description_gr');
            $table->dropColumn('description_ru');
            $table->dropColumn('description_it');
            $table->dropColumn('description_fr');
            $table->dropColumn('description_ge');
            $table->dropColumn('toponym_gr');
            $table->dropColumn('toponym_ru');
            $table->dropColumn('toponym_it');
            $table->dropColumn('toponym_fr');
            $table->dropColumn('toponym_ge');
        });

        Schema::table('sitecategories', function (Blueprint $table) {
            $table->dropColumn('name_gr');
            $table->dropColumn('name_ru');
            $table->dropColumn('name_it');
            $table->dropColumn('name_fr');
            $table->dropColumn('name_ge');
            $table->dropColumn('description_gr');
            $table->dropColumn('description_ru');
            $table->dropColumn('description_it');
            $table->dropColumn('description_fr');
            $table->dropColumn('description_ge');
        });

        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('name_gr');
            $table->dropColumn('name_ru');
            $table->dropColumn('name_it');
            $table->dropColumn('name_fr');
            $table->dropColumn('name_ge');
            $table->dropColumn('town_gr');
            $table->dropColumn('town_ru');
            $table->dropColumn('town_it');
            $table->dropColumn('town_fr');
            $table->dropColumn('town_ge');
        });

        Schema::table('siteworkinghours', function (Blueprint $table) {
            $table->dropColumn('day_gr');
            $table->dropColumn('day_ru');
            $table->dropColumn('day_it');
            $table->dropColumn('day_fr');
            $table->dropColumn('day_ge');
        });

        Schema::table('transportations', function (Blueprint $table) {
            $table->dropColumn('name_gr');
            $table->dropColumn('name_ru');
            $table->dropColumn('name_it');
            $table->dropColumn('name_fr');
            $table->dropColumn('name_ge');
        });
        
        Schema::table('traveltypes', function (Blueprint $table) {
            $table->dropColumn('name_gr');
            $table->dropColumn('name_ru');
            $table->dropColumn('name_it');
            $table->dropColumn('name_fr');
            $table->dropColumn('name_ge');
        });
    }
}

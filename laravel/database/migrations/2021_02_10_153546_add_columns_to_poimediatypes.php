<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPoimediatypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('poimediatypes', function (Blueprint $table) {
            $table->text('name_gr', 500)->nullable();
            $table->text('name_ru', 500)->nullable();
            $table->text('name_it', 500)->nullable();
            $table->text('name_fr', 500)->nullable();
            $table->text('name_ge', 500)->nullable();
            $table->text('description', 5000)->nullable();            
            $table->text('description_gr', 5000)->nullable();
            $table->text('description_ru', 5000)->nullable();
            $table->text('description_it', 5000)->nullable();
            $table->text('description_fr', 5000)->nullable();
            $table->text('description_ge', 5000)->nullable();
            $table->text('path_img', 500)->nullable();
            $table->text('path_thumbnail', 500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('poimediatypes', function (Blueprint $table) {
            $table->dropColumn('name_gr');
            $table->dropColumn('name_ru');
            $table->dropColumn('name_it');
            $table->dropColumn('name_fr');
            $table->dropColumn('name_ge');
            $table->dropColumn('description');
            $table->dropColumn('description_gr');
            $table->dropColumn('description_ru');
            $table->dropColumn('description_it');
            $table->dropColumn('description_fr');
            $table->dropColumn('description_ge');
            $table->dropColumn('path_img');
            $table->dropColumn('path_thumbnail');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteLast3Tables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('user_trackings', function (Blueprint $table) {
            $table->dropForeign(['object_type']);
            $table->dropForeign(['event_type']);
            $table->dropColumn('event_type');
        });

        Schema::dropIfExists('event_types');
        Schema::dropIfExists('object_types');
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

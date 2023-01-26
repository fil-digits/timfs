<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOldDecriptionToMenuItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        if (!Schema::hasColumn('menu_items', 'pos_old_item_description'))
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('pos_old_item_description',100)->nullable()->after('menu_item_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('pos_old_item_description');
        });
    }
}

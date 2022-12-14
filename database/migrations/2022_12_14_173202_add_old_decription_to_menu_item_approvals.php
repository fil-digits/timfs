<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOldDecriptionToMenuItemApprovals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_item_approvals', function (Blueprint $table) {
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
        Schema::table('menu_item_approvals', function (Blueprint $table) {
            $table->dropColumn('pos_old_item_description');
        });
    }
}

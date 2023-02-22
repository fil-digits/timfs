<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVersionIdToMenuIngredientsDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_ingredients_details', function (Blueprint $table) {
            $table->text('version_id')->nullable()->after('item_masters_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_ingredients_details', function (Blueprint $table) {
            $table->dropColumn('version_id');
        });
    }
}

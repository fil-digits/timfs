<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuAsIngredientIdToMenuIngredientsDetailsTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_ingredients_details_temp', function (Blueprint $table) {
            $table->integer('menu_as_ingredient_id')->length(10)->nullable()->after('item_masters_id');
            $table->text('version_id')->nullable()->after('ingredient_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_ingredients_details_temp', function (Blueprint $table) {
            $table->dropColumn('menu_as_ingredient_id');
            $table->dropColumn('version_id');
        });
    }
}

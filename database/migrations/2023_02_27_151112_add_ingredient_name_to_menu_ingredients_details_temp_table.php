<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIngredientNameToMenuIngredientsDetailsTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_ingredients_details_temp', function (Blueprint $table) {
            $table->text('ingredient_name')->nullable()->after('item_masters_id');
            $table->text('uom_name')->nullable()->after('uom_id');
            $table->text('is_existing')->nullable()->after('is_selected');
            $table->integer('item_masters_id')->nullable()->change();
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
            $table->dropColumn('ingredient_name');
            $table->dropColumn('uom_name');
            $table->dropColumn('is_existing');
            $table->integer('item_masters_id')->nullable(false)->change();
        });
    }
}



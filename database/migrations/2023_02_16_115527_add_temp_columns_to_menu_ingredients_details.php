<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTempColumnsToMenuIngredientsDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_ingredients_details', function (Blueprint $table) {
            $table->float('temp_qty')->after('qty');
            $table->float('temp_cost')->after('cost');
            $table->float('is_approved')->after('item_masters_id');
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
            //
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuIngredientsDetailsTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_ingredients_details_temp', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_items_id', false, true)->length(10)->signed()->nullable();
            $table->integer('item_masters_id', false, true)->length(10)->signed()->nullable();
            $table->integer('row_id', false, true)->length(10)->signed()->nullable();
            $table->integer('ingredient_group', false, true)->length(10)->signed()->nullable();
            $table->string('is_primary', 10)->nullable();
            $table->string('is_selected', 10)->nullable();
            $table->string('status', 10)->default('ACTIVE')->nullable();
            $table->float('qty')->nullable();
            $table->integer('uom_id', false, true)->length(10)->unsigned()->nullable();
            $table->float('cost')->nullable();
            $table->float('total_cost')->nullable();
            $table->integer('created_by', false, true)->length(10)->unsigned()->nullable();
            $table->integer('updated_by', false, true)->length(10)->unsigned()->nullable();
            $table->integer('deleted_by', false, true)->length(10)->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_ingredients_details_temp');
    }
}
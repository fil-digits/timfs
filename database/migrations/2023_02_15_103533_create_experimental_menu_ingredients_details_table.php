<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperimentalMenuIngredientsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experimental_menu_ingredients_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('experimental_menu_items_id');
            $table->integer('item_masters_id')->nullable();
            $table->string('ingredient_name')->nullable();
            $table->integer('row_id')->nullable();
            $table->integer('ingredient_group')->nullable();
            $table->string('is_primary')->nullable();
            $table->string('is_selected')->nullable();
            $table->string('is_existing')->nullable();
            $table->float('qty')->nullable();
            $table->integer('uom_id')->nullable();
            $table->string('uom_name')->nullable();
            $table->float('cost')->nullable();
            $table->float('total_cost')->nullable();
            $table->string('status')->default('ACTIVE')->nullable();
            $table->integer('created_by', false, true)->nullable();
            $table->integer('updated_by', false, true)->nullable();
            $table->integer('deleted_by', false, true)->nullable();
            $table->timestamps();
            $table->softdeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experimental_menu_ingredients_details');
    }
}

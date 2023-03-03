<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuIngredientsVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_ingredients_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_items_id')->length(10)->nullable();
            $table->text('ingredients_json')->nullable();
            $table->integer('created_by')->length(10)->nullable();
            $table->integer('deleted_by')->length(10)->nullable();
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
        Schema::dropIfExists('menu_ingredients_versions');
    }
}

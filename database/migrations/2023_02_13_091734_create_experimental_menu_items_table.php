<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperimentalMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experimental_menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('experimental_menu_desc')->nullable();
            $table->string('concept')->nullable();
            $table->decimal('srp', 10, 2)->nullable();
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
        Schema::dropIfExists('experimental_menu_items');
    }
}

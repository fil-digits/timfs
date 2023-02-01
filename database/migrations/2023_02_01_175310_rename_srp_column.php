<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameSrpColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_ingredients_details', function(Blueprint $table) {
            $table->renameColumn('srp', 'cost');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_ingredients_details', function(Blueprint $table) {
            $table->renameColumn('cost', 'srp');
        });
    }
}

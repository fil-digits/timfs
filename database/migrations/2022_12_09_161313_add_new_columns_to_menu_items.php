<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsToMenuItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->integer('menu_types_id')->unsigned()->nullable()->after('menu_categories_id');
            $table->integer('menu_product_types_id')->unsigned()->nullable()->after('menu_types_id');
            $table->integer('menu_transaction_types_id')->unsigned()->nullable()->after('menu_product_types_id');
            $table->string('original_concept',100)->nullable();
            $table->string('available_concepts',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('menu_types_id');
            $table->dropColumn('menu_product_types_id');
            $table->dropColumn('menu_transaction_types_id');
            $table->dropColumn('original_concept');
            $table->dropColumn('available_concepts');
        });
    }
}

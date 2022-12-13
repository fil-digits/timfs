<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPricingHistoryColumnsToHistoryItemMasterfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history_item_masterfile', function (Blueprint $table) {
            $table->integer('group_id', false, true)->length(10)->unsigned()->nullable()->after('brand_id');
            $table->decimal('ttp', 18, 2)->nullable()->after('group_id');
            $table->decimal('ttp_percentage', 18, 2)->nullable()->after('ttp');
            $table->decimal('old_ttp', 18, 2)->nullable()->after('ttp_percentage');
            $table->decimal('old_ttp_percentage', 18, 2)->nullable()->after('old_ttp');
            $table->decimal('purchase_price', 18, 2)->nullable()->after('old_ttp_percentage');
            $table->decimal('old_purchase_price', 18, 2)->nullable()->after('purchase_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history_item_masterfile', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->dropColumn('ttp');
            $table->dropColumn('ttp_percentage');
            $table->dropColumn('old_ttp');
            $table->dropColumn('old_ttp_percentage');
            $table->dropColumn('purchase_price');
            $table->dropColumn('old_purchase_price');
        });
    }
}

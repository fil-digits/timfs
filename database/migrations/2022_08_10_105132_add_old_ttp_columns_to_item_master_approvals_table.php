<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOldTtpColumnsToItemMasterApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_master_approvals', function (Blueprint $table) {
            $table->decimal('old_ttp', 18, 2)->nullable()->after('ttp');
            $table->decimal('old_ttp_percentage', 18, 2)->nullable()->after('ttp_percentage');
            $table->decimal('ttp_price_change', 18, 2)->nullable()->after('ttp');
            $table->date('ttp_price_effective_date')->nullable()->after('ttp_price_change');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_master_approvals', function (Blueprint $table) {
            $table->dropColumn('old_ttp');
            $table->dropColumn('old_ttp_percentage');
            $table->dropColumn('ttp_price_change');
            $table->dropColumn('ttp_price_effective_date');
        });
    }
}

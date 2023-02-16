<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuIngredientsApprovalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_ingredients_approval', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_items_id');
            
            $table->integer('chef_updated_by')->nullable();
            $table->timestamp('chef_updated_at')->nullable();

            $table->string('marketing_approval_status')->nullable();
            $table->string('accounting_approval_status')->nullable();

            $table->integer('marketing_approved_by')->nullable();
            $table->integer('accounting_approved_by')->nullable();

            $table->integer('marketing_rejected_by')->nullable();
            $table->integer('accounting_rejected_by')->nullable();

            $table->timestamp('marketing_approved_at')->nullable();
            $table->timestamp('accounting_approved_at')->nullable();

            $table->timestamp('marketing_rejected_at')->nullable();
            $table->timestamp('accounting_rejected_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_ingredients_approval');
    }
}

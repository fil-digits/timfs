<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsColumnAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings_column_accesses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cms_privileges_id', false, true)->length(10)->unsigned()->nullable();
            $table->text('column_names')->nullable();
            $table->tinyInteger('tasteless_code', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('supplier', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('trademark', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('classification', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('supplier_item_code', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('myob_item_description', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('full_item_description', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('brand_code', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('brand_description', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('group', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('category_code', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('category_description', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('subcategory', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('color_code', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('color_description', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('actual_color', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('size', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('dimension', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('uom', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('packaging', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('vendor_type', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('inventory_type', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('sku_status', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('purchase_price', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('currency', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('price_status', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('tax_code', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('ttp', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('ttp_percentage', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('landed_cost', false, true)->length(3)->unsigned()->default(0);
            $table->tinyInteger('segmentation', false, true)->length(3)->unsigned()->default(0);

            $table->enum('status', array('ACTIVE', 'INACTIVE'))->default('ACTIVE')->nullable();
            $table->integer('created_by', false, true)->length(10)->unsigned()->nullable();
            $table->integer('updated_by', false, true)->length(10)->unsigned()->nullable();
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
        Schema::dropIfExists('settings_column_accesses');
    }
}

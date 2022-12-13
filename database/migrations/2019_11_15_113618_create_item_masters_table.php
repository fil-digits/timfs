<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_masters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('action_type',10)->nullable();
            $table->string('tasteless_code',15)->nullable();

            $table->integer('suppliers_id', false, true)->length(10)->unsigned()->nullable();
            $table->foreign('suppliers_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->integer('trademarks_id', false, true)->length(10)->unsigned()->nullable();
            $table->foreign('trademarks_id')->references('id')->on('trademarks')->onDelete('cascade');
            $table->integer('classifications_id', false, true)->length(10)->unsigned()->nullable();
            $table->foreign('classifications_id')->references('id')->on('classifications')->onDelete('cascade');

            $table->string('supplier_item_code',50)->nullable();
            $table->string('myob_item_description',55)->nullable();
            $table->string('full_item_description',255)->nullable();

            $table->integer('brands_id', false, true)->length(10)->unsigned()->nullable();
            $table->foreign('brands_id')->references('id')->on('brands')->onDelete('cascade');
            $table->integer('groups_id', false, true)->length(10)->unsigned()->nullable();
            $table->foreign('groups_id')->references('id')->on('groups')->onDelete('cascade');
            $table->integer('categories_id', false, true)->length(10)->unsigned()->nullable();
            $table->foreign('categories_id')->references('id')->on('categories')->onDelete('cascade');
            $table->integer('subcategories_id', false, true)->length(10)->unsigned()->nullable();
            $table->foreign('subcategories_id')->references('id')->on('subcategories')->onDelete('cascade');
            $table->integer('types_id', false, true)->length(10)->unsigned()->nullable();
            $table->foreign('types_id')->references('id')->on('types')->onDelete('cascade');
            $table->integer('colors_id', false, true)->length(10)->unsigned()->nullable();
            $table->foreign('colors_id')->references('id')->on('colors')->onDelete('cascade');
            $table->string('actual_color',50)->nullable();
            $table->string('flavor',30)->nullable();
            $table->string('packaging_qty',30)->nullable();
            $table->string('packaging_size',30)->nullable();

            $table->integer('uoms_id', false, true)->length(10)->unsigned()->nullable();
            $table->foreign('uoms_id')->references('id')->on('uoms')->onDelete('cascade');
            $table->integer('packagings_id', false, true)->length(10)->unsigned()->nullable();
            $table->foreign('packagings_id')->references('id')->on('packagings')->onDelete('cascade');
            $table->integer('vendor_types_id', false, true)->length(10)->unsigned()->nullable();
            $table->foreign('vendor_types_id')->references('id')->on('vendor_types')->onDelete('cascade');
            $table->integer('inventory_types_id', false, true)->length(10)->unsigned()->nullable();
            $table->foreign('inventory_types_id')->references('id')->on('inventory_types')->onDelete('cascade');
            $table->integer('sku_statuses_id', false, true)->length(10)->unsigned()->nullable();
            $table->foreign('sku_statuses_id')->references('id')->on('sku_statuses')->onDelete('cascade');
            $table->integer('tax_codes_id', false, true)->length(10)->unsigned()->nullable();
            $table->foreign('tax_codes_id')->references('id')->on('tax_codes')->onDelete('cascade');
            $table->integer('currencies_id', false, true)->length(10)->unsigned()->nullable();
            $table->foreign('currencies_id')->references('id')->on('currencies')->onDelete('cascade');

            $table->decimal('purchase_price', 18, 2)->nullable();
            $table->decimal('ttp', 18, 2)->nullable();
            $table->decimal('ttp_percentage', 18, 2)->nullable();
            $table->decimal('landed_cost', 18, 2)->nullable();

            $table->text('segmentation')->nullable();
            $table->string('segmentation_any',30)->nullable();
            $table->string('segmentation_bbd',30)->nullable();
            $table->string('segmentation_eyb',30)->nullable();
            $table->string('segmentation_cbl',30)->nullable();
            $table->string('segmentation_com',30)->nullable();
            $table->string('segmentation_fmr',30)->nullable();
            $table->string('segmentation_fwb',30)->nullable();
            $table->string('segmentation_fzb',30)->nullable();
            $table->string('segmentation_hmk',30)->nullable();
            $table->string('segmentation_htw',30)->nullable();
            $table->string('segmentation_kkd',30)->nullable();
            $table->string('segmentation_lps',30)->nullable();
            $table->string('segmentation_lbs',30)->nullable();
            $table->string('segmentation_mtd',30)->nullable();
            $table->string('segmentation_ppd',30)->nullable();
            $table->string('segmentation_pze',30)->nullable();
            $table->string('segmentation_psn',30)->nullable();
            $table->string('segmentation_ppr',30)->nullable();
            $table->string('segmentation_rcf',30)->nullable();
            $table->string('segmentation_scb',30)->nullable();
            $table->string('segmentation_sch',30)->nullable();
            $table->string('segmentation_sdh',30)->nullable();
            $table->string('segmentation_smk',30)->nullable();
            $table->string('segmentation_twu',30)->nullable();
            $table->string('segmentation_tbf',30)->nullable();
            $table->string('segmentation_tgd',30)->nullable();
            $table->string('segmentation_wkd',30)->nullable();
            $table->string('segmentation_wks',30)->nullable();
            $table->string('segmentation_wmn',30)->nullable();

            $table->string('segmentation_1',30)->nullable();
            $table->string('segmentation_2',30)->nullable();
            $table->string('segmentation_3',30)->nullable();
            $table->string('segmentation_4',30)->nullable();
            $table->string('segmentation_5',30)->nullable();
            $table->string('segmentation_6',30)->nullable();
            $table->string('segmentation_7',30)->nullable();
            $table->string('segmentation_8',30)->nullable();
            $table->string('segmentation_9',30)->nullable();
            $table->string('segmentation_10',30)->nullable();
            $table->string('segmentation_11',30)->nullable();
            $table->string('segmentation_12',30)->nullable();
            $table->string('segmentation_13',30)->nullable();
            $table->string('segmentation_14',30)->nullable();
            $table->string('segmentation_15',30)->nullable();
            $table->string('segmentation_16',30)->nullable();
            $table->string('segmentation_17',30)->nullable();
            $table->string('segmentation_18',30)->nullable();
            $table->string('segmentation_19',30)->nullable();
            $table->string('segmentation_20',30)->nullable();

            $table->tinyInteger('approval_status', false, true)->length(3)->unsigned()->nullable();
            $table->integer('approved_by', false, true)->length(10)->unsigned()->nullable();
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('item_masters');
    }
}

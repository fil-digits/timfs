<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_item_approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('action_type',10)->nullable();
            $table->string('tasteless_menu_code',15)->nullable();
            $table->string('menu_item_description',150)->nullable();
            $table->integer('menu_categories_id', false, true)->length(10)->unsigned()->nullable();
            //$table->foreign('menu_categories_id')->references('id')->on('menu_categories')->onDelete('cascade');
            $table->integer('menu_subcategories_id', false, true)->length(10)->unsigned()->nullable();
            //$table->foreign('menu_subcategories_id')->references('id')->on('menu_subcategories')->onDelete('cascade');
            $table->integer('tax_codes_id', false, true)->length(10)->unsigned()->nullable();
            //$table->foreign('tax_codes_id')->references('id')->on('tax_codes')->onDelete('cascade');
            $table->enum('status',['ACTIVE','INACTIVE'])->default('ACTIVE');
            $table->decimal('menu_cost_price', 18, 2)->nullable();
            $table->decimal('menu_selling_price', 18, 2)->nullable();

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
            $table->string('segmentation_21',30)->nullable();

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
        Schema::dropIfExists('menu_item_approvals');
    }
}

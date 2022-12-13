<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsPriceAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings_price_accesses', function (Blueprint $table) {
            $table->increments('id');
            
            $table->enum('access_type', array('COLUMN VIEW', 'FORM VIEW'))->nullable();
            $table->text('column_names')->nullable();
            $table->integer('cms_privileges_id', false, true)->length(10)->unsigned()->nullable();
            $table->integer('purchase_price', false, true)->length(1)->unsigned()->default(0);
            $table->integer('ttp', false, true)->length(1)->unsigned()->default(0);
            $table->integer('ttp_percentage', false, true)->length(1)->unsigned()->default(0);
            $table->integer('landed_cost', false, true)->length(1)->unsigned()->default(0);

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
        Schema::dropIfExists('settings_price_accesses');
    }
}

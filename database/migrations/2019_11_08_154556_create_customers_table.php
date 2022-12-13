<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('action_type',10)->nullable();
            $table->integer('channels_id', false, true)->length(10)->unsigned()->nullable();
            $table->string('trade_name',100)->nullable();
            $table->string('mall',60)->nullable();
            $table->string('branch',60)->nullable();
            $table->string('first_name',30)->nullable();
            $table->string('last_name',30)->nullable();
            $table->string('customer_name',150)->nullable();
            $table->integer('concept_groups_id', false, true)->length(10)->unsigned()->nullable();
            $table->integer('segmentations_id', false, true)->length(10)->unsigned()->nullable();
            $table->tinyInteger('approval_status', false, true)->length(3)->unsigned()->nullable();
            $table->integer('approved_by', false, true)->length(10)->unsigned()->nullable();
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('customers');
    }
}

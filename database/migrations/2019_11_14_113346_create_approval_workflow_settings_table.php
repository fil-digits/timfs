<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovalWorkflowSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_workflow_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('workflow_number', false, true)->length(10)->unsigned()->nullable();
            $table->string('action_type',10)->nullable();
            $table->integer('cms_moduls_id', false, true)->length(10)->unsigned()->nullable();
            $table->string('module_name',30)->nullable();
            $table->string('current_state',30)->nullable();
            $table->string('next_state',30)->nullable();
            $table->integer('encoder_privilege_id', false, true)->length(10)->unsigned()->nullable();
            $table->integer('approver_privilege_id', false, true)->length(10)->unsigned()->nullable();
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
        Schema::dropIfExists('approval_workflow_settings');
    }
}

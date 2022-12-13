<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChartAccountApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chart_account_approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('action_type',10)->nullable(); //active
            $table->string('account_number',10)->nullable(); //active
            $table->string('account_name',50)->nullable(); //active
            $table->string('header',5)->nullable(); //active
            $table->decimal('balance',18, 2)->nullable();
            $table->string('account_type',30)->nullable(); //active
            $table->string('last_cheque_number',50)->nullable();
            $table->integer('tax_codes_id', false, true)->length(10)->unsigned()->nullable(); //active
            $table->integer('currencies_id', false, true)->length(10)->unsigned()->nullable(); //active
            $table->string('exchange_account',30)->nullable();
            $table->string('inactive_account',5)->nullable(); //active
            $table->string('b_account_number',10)->nullable();
            $table->string('b_account_name',30)->nullable();
            $table->string('description',150)->nullable();
            $table->string('classification',30)->nullable();
            $table->string('sub_total_header',5)->nullable();
            $table->string('accounts',30)->nullable();
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
        Schema::dropIfExists('chart_account_approvals');
    }
}

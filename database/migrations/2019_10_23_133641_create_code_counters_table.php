<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodeCountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('code_counters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type',25)->nullable();
            $table->integer('code_1',false,true)->length(15)->nullable();
            $table->integer('code_2',false,true)->length(15)->nullable();
            $table->integer('code_3',false,true)->length(15)->nullable();
            $table->integer('code_4',false,true)->length(15)->nullable();
            $table->integer('code_5',false,true)->length(15)->nullable();
            $table->integer('code_6',false,true)->length(15)->nullable();
            $table->integer('code_7',false,true)->length(15)->nullable();
            $table->integer('code_8',false,true)->length(15)->nullable();
            $table->integer('code_9',false,true)->length(15)->nullable();
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
        Schema::dropIfExists('code_counters');
    }
}

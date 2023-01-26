<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuOldCodeMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('menu_old_code_masters')) {
            Schema::create('menu_old_code_masters', function (Blueprint $table) {
                $table->id();
                $table->string('menu_old_code_column_name',35)->nullable();
                $table->string('menu_old_code_column_description',50)->nullable();
                $table->enum('status', array('ACTIVE', 'INACTIVE'))->default('ACTIVE')->nullable();
                $table->integer('created_by', false, true)->length(10)->unsigned()->nullable();
                $table->integer('updated_by', false, true)->length(10)->unsigned()->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_old_code_masters');
    }
}

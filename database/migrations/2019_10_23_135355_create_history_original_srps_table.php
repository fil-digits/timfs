<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryOriginalSrpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_original_srps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tasteless_code',10);
            $table->integer('item_id', false, true)->length(10)->unsigned()->nullable();
            $table->integer('brand_id', false, true)->length(10)->unsigned()->nullable();
            $table->decimal('original_srp',16,2);
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
        Schema::dropIfExists('history_original_srps');
    }
}

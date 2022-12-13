<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryTtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_ttps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tasteless_code',10);
            $table->integer('item_id', false, true)->length(10)->unsigned()->nullable();
            $table->integer('brand_id', false, true)->length(10)->unsigned()->nullable();
            $table->decimal('ttp',16,2);
            $table->decimal('ttp_percentage',16,2);
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
        Schema::dropIfExists('history_ttps');
    }
}

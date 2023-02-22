<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserConceptAcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_concept_acess', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_cms_privileges')->unsigned()->nullable();
            $table->integer('cms_users_id')->unsigned()->nullable();
            $table->text('menu_segmentations_id')->nullable();
            $table->string('status',10)->default('ACTIVE');
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('user_concept_acess');
    }
}

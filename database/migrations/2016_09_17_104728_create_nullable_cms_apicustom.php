<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNullableCmsApicustom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cms_apicustom', function (Blueprint $table) {
            $table->string('permalink')->nullable()->change();
            $table->string('table_name')->nullable()->change();
            $table->string('action')->nullable()->change();
            $table->string('column_name')->nullable()->change();
            $table->string('orderby')->nullable()->change();
            $table->string('sub_query_1')->nullable()->change();
            $table->string('sql_where')->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->string('information')->nullable()->change();
            $table->string('parameter')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cms_settings', function (Blueprint $table) {
            //
        });
    }
}

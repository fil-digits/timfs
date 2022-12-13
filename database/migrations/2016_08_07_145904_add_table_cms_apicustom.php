<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableCmsApicustom extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cms_apicustom', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('permalink')->nullable();
			$table->string('table_name')->nullable();
			$table->string('action')->nullable();
			$table->text('column_name')->nullable();
			$table->string('orderby')->nullable();
			$table->text('sub_query_1')->nullable();
			$table->text('sql_where')->nullable();
			$table->string('name')->nullable();
			$table->string('information')->nullable();
			$table->string('parameter')->nullable();
			
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cms_apicustom');
	}

}

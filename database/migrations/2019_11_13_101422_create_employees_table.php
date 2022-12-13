<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('action_type',10)->nullable(); //active
            $table->string('last_name',100)->nullable(); //active
            $table->string('first_name',100)->nullable(); //active
            $table->string('card_id',7)->nullable(); //active
            $table->string('card_status',5)->nullable(); //active
            $table->integer('currencies_id', false, true)->length(10)->unsigned()->nullable(); //active
            //1ST
            $table->string('address1_line1',100)->nullable(); 
            $table->string('address1_line2',50)->nullable(); 
            $table->string('address1_line3',20)->nullable();
            $table->string('address1_line4',20)->nullable();
            $table->integer('cities_id', false, true)->length(10)->unsigned()->nullable(); 
            $table->integer('states_id', false, true)->length(10)->unsigned()->nullable(); 
            $table->string('post_code',20)->nullable(); 
            $table->integer('countries_id', false, true)->length(10)->unsigned()->nullable(); 
            $table->string('phone_number1',20)->nullable(); 
            $table->string('phone_number2',20)->nullable(); 
            $table->string('phone_number3',20)->nullable(); 
            $table->string('fax_number',20)->nullable(); 
            $table->string('email',30)->nullable(); 
            $table->string('www',20)->nullable();
            $table->string('contact_name',30)->nullable(); 
            $table->string('salutation',5)->nullable(); 
            //2ND
            $table->string('address2_line1',100)->nullable();
            $table->string('address2_line2',20)->nullable();
            $table->string('address2_line3',20)->nullable();
            $table->string('address2_line4',20)->nullable();
            $table->integer('cities2_id', false, true)->length(10)->unsigned()->nullable();
            $table->integer('states2_id', false, true)->length(10)->unsigned()->nullable();
            $table->string('post_code2',20)->nullable();
            $table->integer('countries2_id', false, true)->length(10)->unsigned()->nullable();
            $table->string('phone2_number1',20)->nullable();
            $table->string('phone2_number2',20)->nullable();
            $table->string('phone2_number3',20)->nullable();
            $table->string('fax_number2',20)->nullable();
            $table->string('email2',30)->nullable();
            $table->string('www2',20)->nullable();
            $table->string('contact_name2',30)->nullable();
            $table->string('salutation2',5)->nullable();
            //3RD
            $table->string('address3_line1',100)->nullable();
            $table->string('address3_line2',20)->nullable();
            $table->string('address3_line3',20)->nullable();
            $table->string('address3_line4',20)->nullable();
            $table->integer('cities3_id', false, true)->length(10)->unsigned()->nullable();
            $table->integer('states3_id', false, true)->length(10)->unsigned()->nullable();
            $table->string('post_code3',20)->nullable();
            $table->integer('countries3_id', false, true)->length(10)->unsigned()->nullable();
            $table->string('phone3_number1',20)->nullable();
            $table->string('phone3_number2',20)->nullable();
            $table->string('phone3_number3',20)->nullable();
            $table->string('fax_number3',20)->nullable();
            $table->string('email3',30)->nullable();
            $table->string('www3',20)->nullable();
            $table->string('contact_name3',30)->nullable();
            $table->string('salutation3',5)->nullable();
            //4TH
            $table->string('address4_line1',100)->nullable();
            $table->string('address4_line2',20)->nullable();
            $table->string('address4_line3',20)->nullable();
            $table->string('address4_line4',20)->nullable();
            $table->integer('cities4_id', false, true)->length(10)->unsigned()->nullable();
            $table->integer('states4_id', false, true)->length(10)->unsigned()->nullable();
            $table->string('post_code4',20)->nullable();
            $table->integer('countries4_id', false, true)->length(10)->unsigned()->nullable();
            $table->string('phone4_number1',20)->nullable();
            $table->string('phone4_number2',20)->nullable();
            $table->string('phone4_number3',20)->nullable();
            $table->string('fax_number4',20)->nullable();
            $table->string('email4',30)->nullable();
            $table->string('www4',20)->nullable();
            $table->string('contact_name4',30)->nullable();
            $table->string('salutation4',5)->nullable();
            //5TH
            $table->string('address5_line1',100)->nullable();
            $table->string('address5_line2',20)->nullable();
            $table->string('address5_line3',20)->nullable();
            $table->string('address5_line4',20)->nullable();
            $table->integer('cities5_id', false, true)->length(10)->unsigned()->nullable();
            $table->integer('states5_id', false, true)->length(10)->unsigned()->nullable();
            $table->string('post_code5',20)->nullable();
            $table->integer('countries5_id', false, true)->length(10)->unsigned()->nullable();
            $table->string('phone5_number1',20)->nullable();
            $table->string('phone5_number2',20)->nullable();
            $table->string('phone5_number3',20)->nullable();
            $table->string('fax_number5',20)->nullable();
            $table->string('email5',30)->nullable();
            $table->string('www5',20)->nullable();
            $table->string('contact_name5',30)->nullable();
            $table->string('salutation5',5)->nullable();
            //6TH
            $table->text('picture')->nullable();
            $table->text('notes')->nullable();
            $table->string('identifiers',50)->nullable();
            $table->string('custom_list1',50)->nullable();
            $table->string('custom_list2',50)->nullable();
            $table->string('custom_list3',50)->nullable();
            $table->string('custom_field1',50)->nullable();
            $table->string('custom_field2',50)->nullable();
            $table->string('custom_field3',50)->nullable();
            $table->string('billing_rate',50)->nullable();
            $table->decimal('cost_per_hour',18, 2)->nullable();
            $table->string('record_id',50)->nullable();
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
        Schema::dropIfExists('employees');
    }
}

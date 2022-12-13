<?php namespace App\Http\Controllers;

	use Session;
	//----added by cris 20201006----
    use Illuminate\Http\Request;
    // use Request;
    use Excel;
    //------------------------------
	use DB;
	use CRUDBooster;
	use App\CustomerMyob;
	use App\CustomerMyobApproval;
	use App\ApprovalWorkflowSetting;

	class AdminCustomerMyobsController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}
		
	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "card_id";
			$this->limit = "20";
			$this->orderby = "last_name,asc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "customer_myobs";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
// 			$this->col[] = ["label"=>"Last Name","name"=>"last_name"];
// 			$this->col[] = ["label"=>"First Name","name"=>"first_name"];
// 			$this->col[] = ["label"=>"Card ID","name"=>"card_id"];
// 			$this->col[] = ["label"=>"Card Status","name"=>"card_status"];
// 			$this->col[] = ["label"=>"Currency Code","name"=>"currencies_id","join"=>"currencies,currency_code"];
// 			$this->col[] = ["label"=>"Address1 Line1","name"=>"address1_line1"];
// 			$this->col[] = ["label"=>"Address1 Line2","name"=>"address1_line2"];
// 			$this->col[] = ["label"=>"City","name"=>"cities_id","join"=>"cities,city_name"];
// 			$this->col[] = ["label"=>"State","name"=>"states_id","join"=>"states,state_name"];
// 			$this->col[] = ["label"=>"Post Code","name"=>"post_code"];
// 			$this->col[] = ["label"=>"Country","name"=>"countries_id","join"=>"countries,country_code"];
// 			$this->col[] = ["label"=>"Terms Payment Is Due","name"=>"terms_payment_is_due"];
// 			$this->col[] = ["label"=>"Terms Balance Due Days","name"=>"terms_balance_due_days"];
// 			$this->col[] = ["label"=>"Tax Code","name"=>"tax_codes_id","join"=>"tax_codes,tax_code"];
// 			$this->col[] = ["label"=>"Tax ID No.","name"=>"tax_id_no"];
// 			$this->col[] = ["label"=>"Sales Purchase Layout","name"=>"sales_purchase_layout"];
// 			$this->col[] = ["label"=>"Price Level","name"=>"price_level"];
// 			$this->col[] = ["label"=>"Payment Method","name"=>"payment_method"];
// 			$this->col[] = ["label"=>"Freight Tax Code","name"=>"freight_tax_code"];
// 			$this->col[] = ["label"=>"Use Customer Tax Code","name"=>"use_customer_tax_code"];
// 			$this->col[] = ["label"=>"Invoice Purchase Order Delivery","name"=>"invoice_purchase_order_delivery"];
// 			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
// 			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
// 			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
// 			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];

//          hided by cris

//             $this->col[] = ["label"=>"Co./Last Name","name"=>"last_name"];
// 			$this->col[] = ["label"=>"First Name","name"=>"first_name","visible"=>false];
// 			$this->col[] = ["label"=>"Card Id","name"=>"card_id"];
// 			$this->col[] = ["label"=>"Card Status","name"=>"card_status"];
// 			$this->col[] = ["label"=>"Currency Code","name"=>"currencies_id","join"=>"currencies,currency_code"];
// 			$this->col[] = ["label"=>"Address1 Line1","name"=>"address1_line1","visible"=>false];
// 			$this->col[] = ["label"=>"Address1 Line2","name"=>"address1_line2","visible"=>false];
// 			$this->col[] = ["label"=>"Address1 Line3","name"=>"address1_line3","visible"=>false];
// 			$this->col[] = ["label"=>"Address1 Line4","name"=>"address1_line4","visible"=>false];
// 			$this->col[] = ["label"=>"City","name"=>"cities_id","join"=>"cities,city_name","visible"=>false];
// 			$this->col[] = ["label"=>"State","name"=>"states_id","join"=>"states,state_name","visible"=>false];
// 			$this->col[] = ["label"=>"Post Code","name"=>"post_code","visible"=>false];
// 			$this->col[] = ["label"=>"Country","name"=>"countries_id","join"=>"countries,country_name","visible"=>false];
// 			$this->col[] = ["label"=>"Phone #1","name"=>"phone_number1","visible"=>false];
// 			$this->col[] = ["label"=>"Phone #2","name"=>"phone_number2","visible"=>false];
// 			$this->col[] = ["label"=>"Phone #3","name"=>"phone_number3","visible"=>false];			
// 			$this->col[] = ["label"=>"Fax #","name"=>"fax_number","visible"=>false];
// 			$this->col[] = ["label"=>"Email","name"=>"email","visible"=>false];
// 			$this->col[] = ["label"=>"WWW","name"=>"www","visible"=>false];
// 			$this->col[] = ["label"=>"Contact Name","name"=>"contact_name","visible"=>false];
// 			$this->col[] = ["label"=>"Salutation","name"=>"salutation","visible"=>false];
// 			$this->col[] = ["label"=>"Address2 Line1","name"=>"address2_line1","visible"=>false];
// 			$this->col[] = ["label"=>"Address2 Line2","name"=>"address2_line2","visible"=>false];
// 			$this->col[] = ["label"=>"Address2 Line3","name"=>"address2_line3","visible"=>false];
// 			$this->col[] = ["label"=>"Address2 Line4","name"=>"address2_line4","visible"=>false];
// 			$this->col[] = ["label"=>"City","name"=>"cities2_id","join"=>"cities,city_name","visible"=>false];
// 			$this->col[] = ["label"=>"State","name"=>"states2_id","join"=>"states,state_name","visible"=>false];
// 			$this->col[] = ["label"=>"Post Code","name"=>"post_code2","visible"=>false];
// 			$this->col[] = ["label"=>"Country","name"=>"countries2_id","join"=>"countries,country_name","visible"=>false];
// 			$this->col[] = ["label"=>"Phone #1","name"=>"phone2_number1","visible"=>false];
// 			$this->col[] = ["label"=>"Phone #2","name"=>"phone2_number2","visible"=>false];
// 			$this->col[] = ["label"=>"Phone #3","name"=>"phone2_number3","visible"=>false];			
// 			$this->col[] = ["label"=>"Fax #","name"=>"fax_number2","visible"=>false];
// 			$this->col[] = ["label"=>"Email","name"=>"email2","visible"=>false];
// 			$this->col[] = ["label"=>"WWW","name"=>"www2","visible"=>false];
// 			$this->col[] = ["label"=>"Contact Name","name"=>"contact_name2","visible"=>false];
// 			$this->col[] = ["label"=>"Salutation","name"=>"salutation2","visible"=>false];
// 			$this->col[] = ["label"=>"Address3 Line1","name"=>"address3_line1","visible"=>false];
// 			$this->col[] = ["label"=>"Address3 Line2","name"=>"address3_line2","visible"=>false];
// 			$this->col[] = ["label"=>"Address3 Line3","name"=>"address3_line3","visible"=>false];
// 			$this->col[] = ["label"=>"Address3 Line4","name"=>"address3_line4","visible"=>false];
// 			$this->col[] = ["label"=>"City","name"=>"cities3_id","join"=>"cities,city_name","visible"=>false];
// 			$this->col[] = ["label"=>"State","name"=>"states3_id","join"=>"states,state_name","visible"=>false];
// 			$this->col[] = ["label"=>"Post Code","name"=>"post_code3","visible"=>false];
// 			$this->col[] = ["label"=>"Country","name"=>"countries3_id","join"=>"countries,country_name","visible"=>false];
// 			$this->col[] = ["label"=>"Phone #1","name"=>"phone3_number1","visible"=>false];
// 			$this->col[] = ["label"=>"Phone #2","name"=>"phone3_number2","visible"=>false];
// 			$this->col[] = ["label"=>"Phone #3","name"=>"phone3_number3","visible"=>false];			
// 			$this->col[] = ["label"=>"Fax #","name"=>"fax_number3","visible"=>false];
// 			$this->col[] = ["label"=>"Email","name"=>"email3","visible"=>false];
// 			$this->col[] = ["label"=>"WWW","name"=>"www3","visible"=>false];
// 			$this->col[] = ["label"=>"Contact Name","name"=>"contact_name3","visible"=>false];
// 			$this->col[] = ["label"=>"Salutation","name"=>"salutation3","visible"=>false];
// 			$this->col[] = ["label"=>"Address4 Line1","name"=>"address4_line1","visible"=>false];
// 			$this->col[] = ["label"=>"Address4 Line2","name"=>"address4_line2","visible"=>false];
// 			$this->col[] = ["label"=>"Address4 Line3","name"=>"address4_line3","visible"=>false];
// 			$this->col[] = ["label"=>"Address4 Line4","name"=>"address4_line4","visible"=>false];
// 			$this->col[] = ["label"=>"City","name"=>"cities4_id","join"=>"cities,city_name","visible"=>false];
// 			$this->col[] = ["label"=>"State","name"=>"states4_id","join"=>"states,state_name","visible"=>false];
// 			$this->col[] = ["label"=>"Post Code","name"=>"post_code4","visible"=>false];
// 			$this->col[] = ["label"=>"Country","name"=>"countries4_id","join"=>"countries,country_name","visible"=>false];
// 			$this->col[] = ["label"=>"Phone #1","name"=>"phone4_number1","visible"=>false];
// 			$this->col[] = ["label"=>"Phone #2","name"=>"phone4_number2","visible"=>false];
// 			$this->col[] = ["label"=>"Phone #3","name"=>"phone4_number3","visible"=>false];			
// 			$this->col[] = ["label"=>"Fax #","name"=>"fax_number4","visible"=>false];
// 			$this->col[] = ["label"=>"Email","name"=>"email4","visible"=>false];
// 			$this->col[] = ["label"=>"WWW","name"=>"www4","visible"=>false];
// 			$this->col[] = ["label"=>"Contact Name","name"=>"contact_name4","visible"=>false];
// 			$this->col[] = ["label"=>"Salutation","name"=>"salutation4","visible"=>false];
// 			$this->col[] = ["label"=>"Address5 Line1","name"=>"address5_line1","visible"=>false];
// 			$this->col[] = ["label"=>"Address5 Line2","name"=>"address5_line2","visible"=>false];
// 			$this->col[] = ["label"=>"Address5 Line3","name"=>"address5_line3","visible"=>false];
// 			$this->col[] = ["label"=>"Address5 Line4","name"=>"address5_line4","visible"=>false];
// 			$this->col[] = ["label"=>"City","name"=>"cities5_id","join"=>"cities,city_name","visible"=>false];
// 			$this->col[] = ["label"=>"State","name"=>"states5_id","join"=>"states,state_name","visible"=>false];
// 			$this->col[] = ["label"=>"Post Code","name"=>"post_code5","visible"=>false];
// 			$this->col[] = ["label"=>"Country","name"=>"countries5_id","join"=>"countries,country_name","visible"=>false];
// 			$this->col[] = ["label"=>"Phone #1","name"=>"phone5_number1","visible"=>false];
// 			$this->col[] = ["label"=>"Phone #2","name"=>"phone5_number2","visible"=>false];
// 			$this->col[] = ["label"=>"Phone #3","name"=>"phone5_number3","visible"=>false];			
// 			$this->col[] = ["label"=>"Fax #","name"=>"fax_number5","visible"=>false];
// 			$this->col[] = ["label"=>"Email","name"=>"email5","visible"=>false];
// 			$this->col[] = ["label"=>"WWW","name"=>"www5","visible"=>false];
// 			$this->col[] = ["label"=>"Contact Name","name"=>"contact_name5","visible"=>false];
// 			$this->col[] = ["label"=>"Salutation","name"=>"salutation5","visible"=>false];
// 			$this->col[] = ["label"=>"Picture","name"=>"picture","visible"=>false];
// 			$this->col[] = ["label"=>"Notes","name"=>"notes","visible"=>false];
// 			$this->col[] = ["label"=>"Identifiers","name"=>"identifiers","visible"=>false];
// 			$this->col[] = ["label"=>"Custom List 1","name"=>"custom_list1","visible"=>false];
// 			$this->col[] = ["label"=>"Custom List 2","name"=>"custom_list2","visible"=>false];
// 			$this->col[] = ["label"=>"Custom List 3","name"=>"custom_list3","visible"=>false];
// 			$this->col[] = ["label"=>"Custom Field 1","name"=>"custom_field1","visible"=>false];
// 			$this->col[] = ["label"=>"Custom Field 2","name"=>"custom_field2","visible"=>false];
// 			$this->col[] = ["label"=>"Custom Field 3","name"=>"custom_field3","visible"=>false];
// 			$this->col[] = ["label"=>"Billing Rate","name"=>"billing_rate","visible"=>false];
// 			$this->col[] = ["label"=>"Terms-Payment is Due","name"=>"terms_payment_is_due"];
// 			$this->col[] = ["label"=>"Discount Days","name"=>"terms_discount_days","visible"=>false];
// 			$this->col[] = ["label"=>"Balance Due Days","name"=>"terms_balance_due_days","visible"=>false];
// 			$this->col[] = ["label"=>"Discount","name"=>"terms_discount","visible"=>false];
// 			$this->col[] = ["label"=>"Monthly Charge","name"=>"terms_monthly_charge","visible"=>false];
// 			$this->col[] = ["label"=>"Tax Code","name"=>"tax_codes_id","join"=>"tax_codes,tax_code"];
// 			$this->col[] = ["label"=>"Credit Limit","name"=>"credit_limit","visible"=>false];
// 			$this->col[] = ["label"=>"Tax ID No.","name"=>"tax_id_no","visible"=>false];
// 			$this->col[] = ["label"=>"Volume Discount%","name"=>"volume_discount","visible"=>false];
// 			$this->col[] = ["label"=>"Sales/Purchase Layout","name"=>"sales_purchase_layout"];
// 			$this->col[] = ["label"=>"Price Level","name"=>"price_level"];
// 			$this->col[] = ["label"=>"Payment Method","name"=>"payment_method"];
// 			$this->col[] = ["label"=>"Payment Notes","name"=>"payment_notes","visible"=>false];
// 			$this->col[] = ["label"=>"Name on Card","name"=>"name_on_card","visible"=>false];
// 			$this->col[] = ["label"=>"Card Number","name"=>"card_number","visible"=>false];
// 			$this->col[] = ["label"=>"Expiry Date","name"=>"expiry_date","visible"=>false];
// 			$this->col[] = ["label"=>"Account","name"=>"account","visible"=>false];
// 			$this->col[] = ["label"=>"Salesperson","name"=>"sales_person","visible"=>false];
// 			$this->col[] = ["label"=>"Salesperson Card ID","name"=>"sales_person_card_id","visible"=>false];
// 			$this->col[] = ["label"=>"Comment","name"=>"comment","visible"=>false];
// 			$this->col[] = ["label"=>"Shipping Method","name"=>"shipping_method","visible"=>false];
// 			$this->col[] = ["label"=>"Printed Form","name"=>"printed_form","visible"=>false];
// 			$this->col[] = ["label"=>"Freight Tax Code","name"=>"freight_tax_code","join"=>"tax_codes,tax_code"];
// 			$this->col[] = ["label"=>"Use Customers Tax Code","name"=>"use_customer_tax_code"];
// 			$this->col[] = ["label"=>"Receipt Memo","name"=>"receipt_memo","visible"=>false];
// 			$this->col[] = ["label"=>"Invoice/Purchase Order Delivery","name"=>"invoice_purchase_order_delivery"];
// 			$this->col[] = ["label"=>"Record ID","name"=>"record_id","visible"=>false];
// 			$this->col[] = ["label"=>"Payment Notes","name"=>"payment_notes2","visible"=>false];
// 			$this->col[] = ["label"=>"Name on Card","name"=>"name_on_card2","visible"=>false];
// 			$this->col[] = ["label"=>"Card Number","name"=>"card_number2","visible"=>false];
// 			$this->col[] = ["label"=>"Expiry Date","name"=>"expiry_date2","visible"=>false];
// 			$this->col[] = ["label"=>"Account","name"=>"account2","visible"=>false];
// 			$this->col[] = ["label"=>"Salesperson","name"=>"sales_person2","visible"=>false];
// 			$this->col[] = ["label"=>"Salesperson Card ID","name"=>"sales_person_card_id2","visible"=>false];
// 			$this->col[] = ["label"=>"Comment","name"=>"comment2","visible"=>false];
// 			$this->col[] = ["label"=>"Shipping Method","name"=>"shipping_method2","visible"=>false];
// 			$this->col[] = ["label"=>"Printed Form","name"=>"printed_form2","visible"=>false];
// 			$this->col[] = ["label"=>"Freight Tax Code","name"=>"freight_tax_code2","visible"=>false];
// 			$this->col[] = ["label"=>"Use Customers Tax Code","name"=>"use_customer_tax_code2","visible"=>false];
// 			$this->col[] = ["label"=>"Receipt Memo","name"=>"receipt_memo2","visible"=>false];
// 			$this->col[] = ["label"=>"Invoice/Purchase Order Delivery","name"=>"invoice_purchase_order_delivery2","visible"=>false];
// 			$this->col[] = ["label"=>"Record ID","name"=>"record_id2","visible"=>false];	

            //  //----added by cris 20200708--------
                 
                    $this->col[] = ["label"=>"Card ID","name"=>"card_id", "visible"=>false];
            		$this->col[] = ["label"=>"Card Status","name"=>"card_status", "visible"=>false];
            		$this->col[] = ["label" => "Customer", "name" => "customer"];
            		$this->col[] = ["label" => "Currency Code", "name" => "currencies_id", "join" => "currencies,currency_code", "visible"=>false];
            		$this->col[] = ["label" => "Balance", "name" => "balance", "visible"=>false];
            		$this->col[] = ["label" => "Balance Total", "name" => "balance_total","visible"=>false];
            		$this->col[] = ["label" => "Balance (PHP)", "name" => "balance_php","visible"=>false];
            		$this->col[] = ["label" => "Balance Total (PHP)", "name" => "balance_total_php","visible"=>false];
            		$this->col[] = ["label" => "Company", "name" => "company"];
            		$this->col[] = ["label" => "Mr./Ms./...", "name" => "mr_ms", "visible"=>false];
            		$this->col[] = ["label" => "First Name", "name" => "first_name1"];
            		$this->col[] = ["label" => "MI", "name" => "middle_name1", "visible"=>false];
            		$this->col[] = ["label" => "Last Name", "name" => "last_name1"];
            		$this->col[] = ["label"=>"Primary Contact","name"=>"phone_number2", "visible"=>false];
            		$this->col[] = ["label"=>"Main Phone","name"=>"phone_number1", "visible"=>false];
            		$this->col[] = ["label"=>"Fax","name"=>"fax_number", "visible"=>false];
            		$this->col[] = ["label"=>"Alt Phone","name"=>"phone2_number1", "visible"=>false];
            		$this->col[] = ["label"=>"Secondary Contact","name"=>"phone_number3", "visible"=>false];
            		$this->col[] = ["label"=>"Job Title","name"=>"job_title", "visible"=>false];
            		$this->col[] = ["label"=>"Main Email","name"=>"email", "visible"=>false];
            		$this->col[] = ["label"=>"Bill From 1","name"=>"bill_from1", "visible"=>false];
            		$this->col[] = ["label"=>"Bill From 2","name"=>"bill_from2", "visible"=>false];
            		$this->col[] = ["label"=>"Bill From 3","name"=>"bill_from3", "visible"=>false];
            		$this->col[] = ["label"=>"Bill From 4","name"=>"bill_from4", "visible"=>false];
            		$this->col[] = ["label"=>"Bill From 5","name"=>"bill_from5", "visible"=>false];
            		$this->col[] = ["label"=>"Ship From 1","name"=>"ship_from1"];
            		$this->col[] = ["label"=>"Ship From 2","name"=>"bill_from2"];
            		$this->col[] = ["label"=>"Ship From 3","name"=>"bill_from3"];
            		$this->col[] = ["label"=>"Ship From 4","name"=>"bill_from4"];
            		$this->col[] = ["label"=>"Ship From 5","name"=>"bill_from5", "visible"=>false];
            		$this->col[] = ["label"=>"Customer Type","name"=>"customer_type"];
            		$this->col[] = ["label"=>"Terms","name"=>"terms_id", "join"=>"terms,terms_description"];
            		$this->col[] = ["label"=>"Rep","name"=>"rep", "visible"=>false];
            		$this->col[] = ["label"=>"Sales Tax Code","name"=>"sales_tax_code", "visible"=>false];
            		$this->col[] = ["label"=>"Tax Item","name"=>"tax_item", "visible"=>false];
            		$this->col[] = ["label"=>"Resale Num","name"=>"resale_num", "visible"=>false];
            		$this->col[] = ["label"=>"Account No.","name"=>"account_number", "visible"=>false];
            		$this->col[] = ["label"=>"Credit Limit","name"=>"credit_limit"];
            		$this->col[] = ["label"=>"Job Status","name"=>"job_status", "visible"=>false];
            		$this->col[] = ["label"=>"Job Type","name"=>"job_type","visible"=>false];
            		$this->col[] = ["label"=>"Job Description","name"=>"job_description","visible"=>false];
            		$this->col[] = ["label"=>"Start Date","name"=>"start_date","visible"=>false];
            		$this->col[] = ["label"=>"Projected End","name"=>"projected_end","visible"=>false];
            		$this->col[] = ["label"=>"End Date","name"=>"end_date","visible"=>false];
                    
            //-----------------------------------

			if(CRUDBooster::isSuperadmin()) {
				$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
				$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
				# END COLUMNS DO NOT REMOVE THIS LINE
			}
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			//-----added by cris 20200708-------------------
              if (in_array(CRUDBooster::getCurrentMethod(), ['getEdit', 'postEditSave'])) {
            $this->form[] = ['label' => 'Card ID', 'name' => 'card_id', 'type' => 'text', 'validation' => 'required|min:6|max:7|unique:customer_myobs,card_id', 'width' => 'col-sm-4','readonly'=>true];
    		}
            $this->form[] = ['label' => 'Card Status', 'name' => 'card_status', 'type' => 'select', 'validation' => 'required', 'width' => 'col-sm-4', 'dataenum' => 'ACTIVE;NON-ACTIVE'];//, 'help' => 'If Active = N, If Inactive = Y'
            $this->form[] = ['label' => 'Customer', 'name' => 'customer', 'type' => 'text', 'validation' => 'required|min:2|max:100', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Currency', 'name' => 'currencies_id', 'type' => 'select2', 'validation' => 'required|integer|min:0', 'width' => 'col-sm-4', 'datatable' => 'currencies,currency_code', 'datatable_where' => "status = 'ACTIVE'"];
            // $this->form[] = ['label' => 'Balance', 'name' => 'balance', 'type' => 'number', 'validation' => 'integer|min:0', 'width' => 'col-sm-4'];
            // $this->form[] = ['label' => 'Balance Total', 'name' => 'balance_total', 'type' => 'number', 'validation' => 'integer|min:0', 'width' => 'col-sm-4'];
            // $this->form[] = ['label' => 'Balance (PHP)', 'name' => 'balance_php', 'type' => 'number', 'validation' => 'integer|min:0', 'width' => 'col-sm-4'];
            // $this->form[] = ['label' => 'Balance Total (PHP)', 'name' => 'balance_total_php', 'type' => 'number', 'validation' => 'integer|min:0', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Company', 'name' => 'company', 'type' => 'text', 'validation' => 'required|min:2|max:100', 'width' => 'col-sm-4', 'readonly'=>false];
            $this->form[] = ['label' => 'Mr./Ms./...', 'name' => 'mr_ms', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'First Name', 'name' => 'first_name1', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'M.I.', 'name' => 'middle_name1', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Last Name', 'name' => 'last_name1', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Primary Contact', 'name' => 'phone_number2', 'type' => 'text', 'validation' => 'required|min:4|max:20', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Main Phone', 'name' => 'phone_number1', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Fax', 'name' => 'fax_number', 'type' => 'text', 'validation' => 'min:7|max:20', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Alt Phone', 'name' => 'phone2_number1', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Secondary Contact', 'name' => 'phone_number3', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Job Title', 'name' => 'job_title', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Main Email', 'name' => 'email', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Bill From 1', 'name' => 'bill_from1', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4','readonly'=>true];
            $this->form[] = ['label' => 'Bill From 2', 'name' => 'bill_from2', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Bill From 3', 'name' => 'bill_from3', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Bill From 4', 'name' => 'bill_from4', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Bill From 5', 'name' => 'bill_from5', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Ship From 1', 'name' => 'ship_from1', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Ship From 2', 'name' => 'ship_from2', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Ship From 3', 'name' => 'ship_from3', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Ship From 4', 'name' => 'ship_from4', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Ship From 5', 'name' => 'ship_from5', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label'=>'Customer Type','name'=>'customer_type','type'=>'select','validation'=>'required','width'=>'col-sm-4', 'dataenum'=>'COMMISSARY;EXTERNAL;FRANCHISED;OWNED;TENANT;PARTNERSHIP'];
            $this->form[] = ['label'=>'Terms','name'=>'terms_id','type'=>'select2','validation'=>'required','datatable'=>'terms,terms_description','datatable_where'=>'status = "ACTIVE"','width'=>'col-sm-4'];
            $this->form[] = ['label' => 'Rep', 'name' => 'rep', 'type' => 'text', 'width' => 'col-sm-4'];
             if (in_array(CRUDBooster::getCurrentMethod(), ['getEdit', 'postEditSave'])) {
                $this->form[] = ['label'=>'Sales Tax Code','name'=>'sales_tax_code','type'=>'select2','validation'=>'required','datatable'=>'tax_codes,tax_description','datatable_where'=>'status = "ACTIVE"','width'=>'col-sm-4'];
            }else if(in_array(CRUDBooster::getCurrentMethod(), ['getDetail'])){
                $this->form[] = ['label'=>'Sales Tax Code','name'=>'sales_tax_code','type'=>'select2','validation'=>'required','datatable'=>'tax_codes,tax_description','datatable_where'=>'status = "ACTIVE"','width'=>'col-sm-4'];
            }
            else{
                $this->form[] = ['label'=>'Sales Tax Code','name'=>'sales_tax_code','type'=>'select','validation'=>'required','dataenum'=>'NON;TAX','width'=>'col-sm-4'];
            }
            $this->form[] = ['label'=>'Tax Item','name'=>'tax_item','type'=>'select','validation'=>'required','dataenum'=> 'OUTPUT VAT;NONE','width'=>'col-sm-4'];
            // $this->form[] = ['label' => 'Resale Num', 'name' => 'resale_num', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Account No.', 'name' => 'account_number', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Credit Limit', 'name' => 'credit_limit', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Job Status', 'name' => 'job_status', 'type' => 'text', 'width' => 'col-sm-4'];
            // $this->form[] = ['label' => 'Job Type', 'name' => 'job_type', 'type' => 'text', 'width' => 'col-sm-4'];
            // $this->form[] = ['label' => 'Job Description', 'name' => 'job_description', 'type' => 'text', 'width' => 'col-sm-4'];
            // $this->form[] = ['label' => 'Start Date', 'name' => 'start_date', 'type' => 'text', 'width' => 'col-sm-4'];
            // $this->form[] = ['label' => 'Projected End', 'name' => 'projected_end', 'type' => 'text', 'width' => 'col-sm-4'];
            // $this->form[] = ['label' => 'End Date', 'name' => 'end_date', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Channel', 'name' => 'channels_id', 'type' => 'select2', 'validation' => 'required|integer|min:0', 'width' => 'col-sm-4', 'datatable' => 'channels,channel_description'];
    // 		$this->form[] = ['label' => 'TRS Customer Name', 'name' => 'trs_customer_name', 'type' => 'text', 'validation' => 'max:100|required', 'width' => 'col-sm-4'];
            //----------------------------------------------
            
            
//          hided by cris
// 			$this->form[] = ['label'=>'Last Name','name'=>'last_name','type'=>'text','validation'=>'required|min:2|max:100','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'First Name','name'=>'first_name','type'=>'text','validation'=>'max:100','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Card ID','name'=>'card_id','type'=>'text','validation'=>'required|min:6|max:7|unique:customer_myobs,card_id','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Card Status','name'=>'card_status','type'=>'select','validation'=>'required','width'=>'col-sm-4','dataenum'=>'N;Y','help'=>'If Active = N, If Inactive = Y'];
// 			$this->form[] = ['label'=>'Currency','name'=>'currencies_id','type'=>'select2','validation'=>'integer|min:0','width'=>'col-sm-4','datatable'=>'currencies,currency_code','datatable_where'=>"status = 'ACTIVE'"];
// 			$this->form[] = ['label'=>'Address1 Line1','name'=>'address1_line1','type'=>'text','validation'=>'max:100','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Address1 Line2','name'=>'address1_line2','type'=>'text','validation'=>'max:50','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'City','name'=>'cities_id','type'=>'select2','validation'=>'integer|min:0','width'=>'col-sm-4','datatable'=>'cities,city_name','datatable_where'=>"status = 'ACTIVE'"];
// 			$this->form[] = ['label'=>'States','name'=>'states_id','type'=>'select2','validation'=>'integer|min:0','width'=>'col-sm-4','datatable'=>'states,state_name','datatable_where'=>"status = 'ACTIVE'"];
// 			$this->form[] = ['label'=>'Post Code','name'=>'post_code','type'=>'text','validation'=>'min:4|max:20','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Country','name'=>'countries_id','type'=>'select2','validation'=>'integer|min:0','width'=>'col-sm-4','datatable'=>'countries,country_name','datatable_where'=>"status = 'ACTIVE'"];
// 			$this->form[] = ['label'=>'Address2 Line1','name'=>'address2_line1','type'=>'text','validation'=>'max:100','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Address2 Line2','name'=>'address2_line2','type'=>'text','validation'=>'max:50','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'City','name'=>'cities2_id','type'=>'select2','validation'=>'integer|min:0','width'=>'col-sm-4','datatable'=>'cities,city_name','datatable_where'=>"status = 'ACTIVE'"];
// 			$this->form[] = ['label'=>'States','name'=>'states2_id','type'=>'select2','validation'=>'integer|min:0','width'=>'col-sm-4','datatable'=>'states,state_name','datatable_where'=>"status = 'ACTIVE'"];
// 			$this->form[] = ['label'=>'Post Code','name'=>'post_code2','type'=>'text','validation'=>'min:4|max:20','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Country','name'=>'countries2_id','type'=>'select2','validation'=>'integer|min:0','width'=>'col-sm-4','datatable'=>'countries,country_name','datatable_where'=>"status = 'ACTIVE'"];
// 			$this->form[] = ['label'=>'Terms Payment Is Due','name'=>'terms_payment_is_due','type'=>'select','validation'=>'required|integer|min:0','width'=>'col-sm-4','help'=>'If MYOB Format for COD = 1, If MYOB Format on a Day of the Month = 3','dataenum'=>'1;3'];
// 			$this->form[] = ['label'=>'Terms Balance Due Days','name'=>'terms_balance_due_days','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Tax Code','name'=>'tax_codes_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-4','datatable'=>'tax_codes,tax_code','datatable_where'=>"status = 'ACTIVE' and tax_code != 'FRE'"];
// 			$this->form[] = ['label'=>'Tax ID No.','name'=>'tax_id_no','type'=>'text','validation'=>'min:15|max:16','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Sales Purchase Layout','name'=>'sales_purchase_layout','type'=>'select','validation'=>'required','width'=>'col-sm-4','dataenum'=>'I;S','help'=>'If Item = I, If Service = S'];
// 			$this->form[] = ['label'=>'Price Level','name'=>'price_level','type'=>'select','validation'=>'required|integer|min:0','width'=>'col-sm-4','dataenum'=>'1;2'];
// 			$this->form[] = ['label'=>'Payment Method','name'=>'payment_method','type'=>'select','validation'=>'required','width'=>'col-sm-4','dataenum'=>'CHECK;CASH'];
// 			$this->form[] = ['label'=>'Freight Tax Code','name'=>'freight_tax_code','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-4','datatable'=>'tax_codes,tax_code','datatable_where'=>"status = 'ACTIVE'"];
// 			$this->form[] = ['label'=>'Use Customer Tax Code','name'=>'use_customer_tax_code','type'=>'select','validation'=>'required','width'=>'col-sm-4','dataenum'=>'N;Y','help'=>'If Active = N, If Inactive = Y'];
// 			$this->form[] = ['label'=>'Receipt Memo','name'=>'receipt_memo','type'=>'text','validation'=>'max:50','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Invoice Purchase Order Delivery','name'=>'invoice_purchase_order_delivery','type'=>'select','validation'=>'required','width'=>'col-sm-4','dataenum'=>'P'];
// 			$this->form[] = ['label'=>'Channel','name'=>'channels_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-4','datatable'=>'channels,channel_description'];
// 			$this->form[] = ['label'=>'TRS Customer Name','name'=>'trs_customer_name','type'=>'text','validation'=>'max:100|required','width'=>'col-sm-4'];
			//$this->form[] = ['label'=>'Mall','name'=>'mall','type'=>'text','validation'=>'max:60','width'=>'col-sm-4'];
			//$this->form[] = ['label'=>'Branch','name'=>'branch','type'=>'text','validation'=>'max:60','width'=>'col-sm-4'];
		    //$this->form[] = ['label'=>'Segmentation','name'=>'segmentations_id','type'=>'select2','validation'=>'integer|min:0','width'=>'col-sm-4','datatable'=>'segmentations,segment_column_description'];
			# END FORM DO NOT REMOVE THIS LINE

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert = array();
	                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();
            if (CRUDBooster::getCurrentMethod() == 'getIndex'){
                $this->index_button[] = ['label' => 'Export TRS', "url" => CRUDBooster::mainpath("export-trs"), "icon" => "fa fa-download"];
                
                //---added by cris 20201006----
                if(CRUDBooster::isSuperadmin() || CRUDBooster::myPrivilegeName() == "Manager (Purchaser)")
                {
                    $this->index_button[] = [
                    "title" => "Update Customer",
                    "label" => "Update Customer",
                    "color" => "success",
                    "icon" => "fa fa-upload", "url" => CRUDBooster::mainpath('update-customer')
                ];
                }
                //-----------------------------
            }


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();     	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        $this->load_js[] = asset("js/customer_myob.js");
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        
	        
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here
	        ///$query->where('customer_myobs.approval_status',1)->orWhere('customer_myobs.approval_status',3);    
			$query->where(function($sub_query){
				$create_customer_myob_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('next_state');
				$update_customer_myob_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('next_state');

				$sub_query->where('customer_myobs.approval_status', 	$create_customer_myob_status);
				$sub_query->orWhere('customer_myobs.approval_status',$update_customer_myob_status);
			});
		}

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        //Your code here
			$postdata["created_by"]				=	CRUDBooster::myId();
			$postdata["encoder_privilege_id"]	=	CRUDBooster::myPrivilegeId();
			$postdata["action_type"]			=	"Create";
			$postdata['approval_status']		=	ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('current_state');
	        
	        //---------added by cris 20200804------
	         $postdata['last_name'] = $postdata['customer'];
	         
	        if($postdata['sales_tax_code'] == 'NON')
    		{
    			$postdata['sales_tax_code'] = '1';
    		}else{
    			$postdata['sales_tax_code'] = '2';
    		}   
    		if($postdata['tax_item'] == 'NONE')
    		{
    		    $postdata['tax_item'] = "";
    		}
    		//-------------------------------------
	   
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        //Your code here
			$customer_details = CustomerMyob::where('id',$id)->get()->toArray();
			//Insert data to temporary table
			CustomerMyobApproval::insert($customer_details);

			$for_approval = CustomerMyobApproval::where('id',$id)->first();
			$approvers = ApprovalWorkflowSetting::where('status','ACTIVE')->where('action_type', 'Create')
							->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->get();

			foreach ($approvers as $approvers_list){
				$approver_privilege_for =	DB::table('cms_privileges')->where('id',$approvers_list->encoder_privilege_id)->first();
				$approver_privilege =		DB::table('cms_privileges')->where('id',$approvers_list->approver_privilege_id)->first();	
			
				if($for_approval->encoder_privilege_id == $approver_privilege_for->id){
					$send_to =	DB::table('cms_users')->where('id_cms_privileges',$approver_privilege->id)->get();
					foreach ($send_to as $send_now){
						$config['content'] = CRUDBooster::myName(). " has created Customer with Card Id ".$for_approval->card_id." at Customer MYOB Module!";
						$config['to'] = CRUDBooster::adminPath('customer_myob_approval?q='.$for_approval->card_id);
						$config['id_cms_users'] = [$send_now->id];
						CRUDBooster::sendNotification($config);	
					}
				}
				
			}

			CRUDBooster::redirect(CRUDBooster::mainpath(),"Your item has been created and pending for approval.","info");
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here
	        
	        //---------added by cris 20200804------
	        if($postdata['sales_tax_code'] == 'NON')
    		{
    			$postdata['sales_tax_code'] = '1';
    		}else{
    			$postdata['sales_tax_code'] = '2';
    		}   
    		if($postdata['tax_item'] == 'NONE')
    		{
    		    $postdata['tax_item'] = "";
    		}
    		//-------------------------------------
	        
			CustomerMyobApproval::where('id',$id)->update([
			    
			    
    			 //   //-----added by cris 20200708--------------
    			    'last_name' 					=> $postdata['last_name'],
                    'card_id' 						=> $postdata['card_id'],
        			'card_status' 					=> $postdata['card_status'],
                    'customer'                    =>    $postdata['customer'],
                    'currencies_id'                    =>    $postdata['currencies_id'],
                    'balance'                    =>    $postdata['balance'],
                    'balance_total'                    =>    $postdata['balance_total'],
                    'balance_php'                    =>    $postdata['balance_php'],
                    'balance_total_php'                    =>    $postdata['balance_total_php'],
                    'company'                    =>    $postdata['company'],
                    'mr_ms'                    =>    $postdata['mr_ms'],
                    'first_name1'                    =>    $postdata['first_name1'],
                    'middle_name1'                    =>    $postdata['middle_name1'],
                    'last_name1'                    =>    $postdata['last_name1'],
                    'phone_number2'                    =>    $postdata['phone_number2'],
                    'phone_number1'                    =>    $postdata['phone_number1'],
                    'fax_number'                    =>    $postdata['fax_number'],
                    'phone2_number1'                    =>    $postdata['phone2_number1'],
                    'phone_number3'                    =>    $postdata['phone_number3'],
                    'job_title'                    =>    $postdata['job_title'],
                    'email'                    =>    $postdata['email'],
                    'bill_from1'                    =>    $postdata['bill_from1'],
                    'bill_from2'                    =>    $postdata['bill_from2'],
                    'bill_from3'                    =>    $postdata['bill_from3'],
                    'bill_from4'                    =>    $postdata['bill_from4'],
                    'bill_from5'                    =>    $postdata['bill_from5'],
                    'ship_from1'                    =>    $postdata['bill_from1'],
                    'bill_from2'                    =>    $postdata['bill_from2'],
                    'bill_from3'                    =>    $postdata['bill_from3'],
                    'bill_from4'                    =>    $postdata['bill_from4'],
                    'bill_from5'                    =>    $postdata['bill_from5'],
                    'customer_type'                    =>    $postdata['customer_type'],
                    'terms_id'					=>	$postdata['terms_id'],
                    'rep'                    =>    $postdata['rep'],
                    'sales_tax_code'                    =>    $postdata['sales_tax_code'],
                    'tax_item'                    =>    $postdata['tax_item'],
                    'resale_num'                    =>    $postdata['resale_num'],
                    'account_number'                    =>    $postdata['account_number'],
                    'credit_limit'                    =>    $postdata['credit_limit'],
                    'job_status'                    =>    $postdata['job_status'],
                    'job_type'                    =>    $postdata['job_type'],
                    'job_description'                    =>    $postdata['job_description'],
                    'start_date'                    =>    $postdata['start_date'],
                    'projected_end'                    =>    $postdata['projected_end'],
                    'end_date'                    =>    $postdata['end_date'],
                    'channels_id'                   => $postdata['channels_id'],
        // 			'trs_customer_name'             => $postdata['trs_customer_name'],
                    //-----------------------------------------
			    
			    //   hided by cris
				// 'last_name' 					=> $postdata['last_name'],
				// 'first_name' 					=> $postdata['first_name'],
				// 'card_id' 						=> $postdata['card_id'],
				// 'card_status' 					=> $postdata['card_status'],
				// 'currencies_id' 				=> $postdata['currencies_id'],
				// 'address1_line1' 				=> $postdata['address1_line1'],
				// 'address1_line2' 				=> $postdata['address1_line2'],
				// 'cities_id' 					=> $postdata['cities_id'],
				// 'states_id' 					=> $postdata['states_id'],
				// 'post_code' 					=> $postdata['post_code'],
				// 'countries_id' 					=> $postdata['countries_id'],
				// 'address2_line1' 				=> $postdata['address2_line1'],
				// 'address2_line2' 				=> $postdata['address2_line2'],
				// 'cities2_id' 					=> $postdata['cities2_id'],
				// 'states2_id' 					=> $postdata['states2_id'],
				// 'post_code2' 					=> $postdata['post_code2'],
				// 'countries2_id' 				=> $postdata['countries2_id'],
				// 'terms_payment_is_due' 			=> $postdata['terms_payment_is_due'],
				// 'terms_balance_due_days' 		=> $postdata['terms_balance_due_days'],
				// 'tax_codes_id' 					=> $postdata['tax_codes_id'],
				// 'tax_id_no' 					=> $postdata['tax_id_no'],
				// 'sales_purchase_layout' 		=> $postdata['sales_purchase_layout'],
				// 'price_level' 					=> $postdata['price_level'],
				// 'payment_method' 				=> $postdata['payment_method'],
				// 'freight_tax_code' 				=> $postdata['freight_tax_code'],
				// 'use_customer_tax_code' 		=> $postdata['use_customer_tax_code'],
				// 'receipt_memo' 					=> $postdata['receipt_memo'],
				// 'invoice_purchase_order_delivery' => $postdata['invoice_purchase_order_delivery'],
				// 'channels_id'                   => $postdata['channels_id'],
				// 'trs_customer_name'             => $postdata['trs_customer_name'],
				'updated_by'					=> CRUDBooster::myId(),
				'encoder_privilege_id'			=> CRUDBooster::myPrivilegeId(),
				'action_type'					=> 'Update',
				'approval_status'				=> ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('current_state')
			]);

			unset($postdata);
			unset($this->arr);
			
			$this->arr["updated_by"] = CRUDBooster::myId();
			$this->arr["encoder_privilege_id"] = CRUDBooster::myPrivilegeId();
			$this->arr["action_type"] = "Update";
			//$this->arr["approval_status"] = 3;
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
			//Your code here 
			$for_approval = CustomerMyobApproval::where('id',$id)->first();
			$approvers = ApprovalWorkflowSetting::where('status','ACTIVE')->where('action_type', 'Update')
							->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->get();

			foreach ($approvers as $approvers_list){
				$approver_privilege_for =	DB::table('cms_privileges')->where('id',$approvers_list->encoder_privilege_id)->first();
				$approver_privilege =		DB::table('cms_privileges')->where('id',$approvers_list->approver_privilege_id)->first();	
			
				if($for_approval->encoder_privilege_id == $approver_privilege_for->id){
					$send_to =	DB::table('cms_users')->where('id_cms_privileges',$approver_privilege->id)->get();
					foreach ($send_to as $send_now){
						$config['content'] = CRUDBooster::myName(). " has edited Customer with Card Id ".$for_approval->card_id." at Customer MYOB Module!";
						$config['to'] = CRUDBooster::adminPath('customer_myob_approval?q='.$for_approval->card_id);
						$config['id_cms_users'] = [$send_now->id];
						CRUDBooster::sendNotification($config);	
					}
				}
				
			}
			CRUDBooster::redirect(CRUDBooster::mainpath(),"Your item has been updated and pending for approval.","info");
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }

		public function getEdit($id) {
			$item_info = CustomerMyobApproval::find($id);
			$supplier_update_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('current_state');

			if ($item_info->approval_status == $supplier_update_status) {
				CRUDBooster::redirect(CRUDBooster::mainpath(""),"You're not allowed to edit pending items for approval.","warning");
			}
			return parent::getEdit($id);
		}
		
		
		public function customExportExcelTRS(){
			$filename = "Export TRS Customer - " . date('Ymd H:i:s') . ".xls";
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=\"$filename\"");

				$data_imfs = DB::table('customer_myobs')
					->whereNull('customer_myobs.deleted_at')
					->select(
						'channels.channel_description as Channel',
						'customer_myobs.trs_customer_name as TRS Customer Name')
						->leftJoin('channels', 'customer_myobs.channels_id', '=', 'channels.id')
						->get();	
	
			$data_imfs = json_decode(json_encode($data_imfs), true);
			//dd($data_imfs);
			$show_column = false;
			if(!empty($data_imfs)) {
				foreach($data_imfs as $imf_item) {
					if(!$show_column) {
						// display field/column names in first row
						echo implode("\t", array_keys($imf_item)) . "\n";
						$show_column = true;
					}
					echo implode("\t", array_values($imf_item)) . "\n";
				}
			}
			exit;
		}
		
		  //----added by cris 20201006
	
    public function updateCustomer() {
        $this->cbLoader();
       
        $data['page_title'] = 'Update Customer';

        // send to front-end view
        $this->cbView("upload.update_customer", $data);
    }

    public function customerUpdate(Request $request) {
        set_time_limit(-1);
        $file = $request->file('import_file');
            

        $validator = \Validator::make(
            [
                'file' => $file,
                'extension' => strtolower($file->getClientOriginalExtension()),
            ],
            [
                'file' => 'required',
                'extension' => 'required|in:csv',
            ]
        );
        
        // dd($validator);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
            CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_upload_price_format_failed"), 'danger');
        }
        
        
        if ($request->hasFile('import_file')) {
            $path = $request->file('import_file')->getRealPath();
            
            $csv = array_map('str_getcsv', file($path));
            
            $dataExcel = Excel::load($path, function($reader) {
            })->get();
            
            //get all Vendor(Lastname)
         
            $in_db = array();
            
            $customer = DB::table('customer_myobs')->select('customer')->where('customer', '!=', null)->get()->toArray();
            // dd(count($tasteless_code));
            for($i = 0; $i < count($customer); $i++)
            {
                 array_push($in_db,$customer[$i]->customer);
            }
       

        
            
            // $unMatch = [];
            // $header = array(
            //     "Active Status",
            //     "Type",
            //     "Item",
            //     "Description",
            //     "Sales Tax Code",
            //     "Account",
            //     "COGS Account",
            //     "Asset Account",
            //     "Accumulated Depreciation",
            //     "Purchase Description",
            //     "Quantity On Hand",
            //     "U/M",
            //     "U/M Set",
            //     "Cost",
            //     "Preferred Vendor",
            //     "Tax Agency",
            //     "Price",
            //     "Reorder Pt (Min)",
            //     "MPN",
            //     "GROUP",
            //     "BARCODE",
            //     "DIMENSION",
            //     "PACKAGING SIZE",
            //     "PACKAGING UOM",
            //     "TAX STATUS",
            //     "SUPPLIERS ITEM CODE");
            
            // for ($i=0; $i < sizeof($csv[0]); $i++) {
            // 	if (!in_array($csv[0][$i], $header)) {
            // 		$unMatch[] = $csv[0][$i];
            // 	}
            // }
        
            // dd($unMatch);
            // // dd("Active Status" === "Active Status");

            // if(!empty($unMatch)) {
                
            // 	return response()->json(['errors' => trans("crudbooster.alert_upload_price_format_failed")]);
            // 	CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_upload_price_format_failed"), 'danger');
            // }

           
            
            if(!empty($dataExcel) && $dataExcel->count() <= 2000) {
                
                $cnt_fail = 0;
                DB::connection()->disableQueryLog();
                
            // 	array_shift($header);// this code removes first element of an array
            // 	$header = array_map('strtolower', $header);// convert all values in an array to lowercase
            // 	$counter = 1;
            
            
                $new_item = [];

                foreach ($dataExcel as $key => $value) {
                    //  dd($value);
                    $check_upload = false;
                    if($value->customer ==''){
                        
                        $cnt_fail++; 
                    }
                    else{

                        $start_date = $value->start_date;
                        $projected_end = $value->projected_end;
                        $end_date = $value->end_date;

                        $tax_code_id = 0;
                        if($value->sales_tax_code == "TAX")
                        {
                            $tax_code_id = 1;
                        }else{
                            $tax_code_id = 2;
                        }

                        if($start_date != null)
                        {
                            $var = $start_date;     
                            $start_date = date("Y-m-d", strtotime($var));
                        }

                        if($projected_end != null)
                        {
                            $var = $projected_end;
                            // $date = str_replace('/', '-', $var);
                            $projected_end = date('Y-m-d', strtotime($var));
                        }

                        if($end_date != null)
                        {
                            $var = $end_date;
                            // $date = str_replace('/', '-', $var);
                            $end_date = date('Y-m-d', strtotime($var));
                        }

                        $terms = strtoupper($value->terms);
                    
                        $currency_id = DB::table('currencies')->where('currency_code',$value->currency)->select('id')->first();
                        $channels_id = DB::table('channels')->where('channel_description',$value->customer_type)->select('id')->first();
                        $terms_id = DB::table('terms')->where('terms_description',$terms)->select('id')->first();
                        // $uom_id = DB::table('uoms')->where('uom_description',$uom)->select('id')->first();
                        // $uom_set_id = DB::table('uoms_set')->where('uom_description',$uom_set)->select('id')->first();
                        // $preferred_vendor_id = DB::table('suppliers')->where('last_name',$value->preferred_vendor)->select('id')->first();
                        // $group_id = DB::table('groups')->where('group_description',$value->group)->select('id')->first();
                        // $packagings_id = DB::table('packagings')->where('packaging_code',$value->packaging_uom)->select('id')->first();

                        if(!in_array($value->customer,$in_db))// if new tasteless_code
                        {
                            array_push($new_item, $value->customer);

                        }
                        
                        $data = [
                            'action_type'               => "Update",
                            'last_name'                => $value->customer,
                            'customer'                 => $value->customer,
                            'currencies_id'             => $currency_id->id,
                            'balance'                   => intval($value->balance),
                            'balance_total'             => intval($value->balance_total),
                            'balance_php'               => intval($value->balance_php),
                            'balance_total_php'         => intval($value->balance_total_php),
                            'company'                   => $value->company,
                            'mr_ms'                     => $value['mr.ms....'],
                            'first_name1'               => $value->first_name,
                            'middle_name1'              => $value['m.i.'],
                            'last_name1'                => $value->last_name,
                            'phone_number2'             => intval($value->primary_contact),
                            'phone_number1'             => intval($value->main_phone),
                            'fax_number'                => intval($value->fax),
                            'phone2_number1'            => intval($value['alt._phone']),
                            'phone_number3'             => intval($value->secondary_contact),
                            'job_title'                 => $value->job_title,
                            'email'                     => $value->main_email,
                            'bill_from1'               => $value->customer,
                            'bill_from2'               => $value->bill_from_2,
                            'bill_from3'               => $value->bill_from_3,
                            'bill_from4'               => $value->bill_from_4,
                            'bill_from5'               => $value->bill_from_5,
                            'ship_from1'               => $value->ship_from_1,
                            'ship_from2'               => $value->ship_from_2,
                            'ship_from3'               => $value->ship_from_3,
                            'ship_from4'               => $value->ship_from_4,
                            'ship_from5'               => $value->ship_from_5,
                            'channels_id'              => $channels_id->id,
                            'terms_id'                 => $terms_id->id,
                            'rep'                      => $value->rep,
                            'sales_tax_code'           => $tax_code_id,
                            'tax_item'                 => $value->tax_item,
                            'resale_num'               => $value->resale_num,
                            'account_number'           => intval($value->account_number),
                            'credit_limit'             => intval($value->credit_limit),
                            'job_status'               => $value->job_status,
                            'job_type'                 => $value->job_type,
                            'job_description'          => $value->job_description,
                            'start_date'               => $start_date,
                            'projected_end'            => $projected_end,
                            'end_date'                 => $end_date,
                            'updated_by'                => CRUDBooster::myId(),
                            'updated_at'                =>  date('Y-m-d H:i:s')
                                
                            ];
                        // dd($data);
                            
                        DB::beginTransaction();			
                        try {
                            // if(!in_array($value->item,$in_db))
                            // {
                            //     //  DB::table('item_masters')->insert($new_data);
                            // }
                            
                            DB::table('customer_myobs')->where('customer', $value->customer)->update($data);
                            DB::commit();
                        } catch (\Exception $e) {
                            dd($e);
                            return response()->json(['errors' => $e]);
                            DB::rollback();
                        }
                    }
                    
                }
                

                if($cnt_fail == 0){
                    
                    if(!empty($new_item))
                    {
                        $str = '';
                        $str = implode(', ',$new_item);
                        
                        Excel::create('new-customer' . date("Ymd") . '-' . date("h.i.sa"), function ($excel) use ($new_item) {
                            $excel->sheet('new_customer', function ($sheet) use ($new_item) {

                                $cnt_item = count($new_item);
                                for($i = 0; $i < $cnt_item; $i++)
                                {
                                    $sheet->prependRow($i+1, [$new_item[$i]]);
                                    // $sheet->row($i+2, function ($row) {
                                        // $row->setBackground('#FFFF00');
                                        // $row->setAlignment('center')s;
                                    // });
                                    
                                }
                            });
                        })->export('xlsx');
                        
                        CRUDBooster::redirect(CRUDBooster::mainpath(), 'Upload success!. New customers found: '. $str. ' please manual add these customers.', 'success');
                        // return response()->json(['success' => trans("crudbooster.alert_upload_price_success"),
                        // 'New items found: ' => $new_item]);
                    }else{
                        CRUDBooster::redirect(CRUDBooster::mainpath(), 'Update customers success!', 'success');
                        // return response()->json(['success' => trans("crudbooster.alert_upload_price_success")]);
                    }
                    
                    // CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_upload_inventory_success"), 'success');
                }
                else{
                    dd("error");
                    
                    // return response()->json(['errors' => trans("crudbooster.alert_upload_price_failed")]);
                    CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_upload_price_failed"), 'danger');
                }

                
            }else{
                CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_more_than_2k_lines"), 'danger');
                return response()->json(['errors' => trans("crudbooster.alert_upload_inventory_beyond_total")]);
                CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_upload_price_failed"), 'danger');
            }
            unset($in_db);
            unset($new_item);
        }
        
    }

    // 	--------------------------
		
	}
<?php namespace App\Http\Controllers;

	use Session;
	//----added by cris 20201006----
    use Illuminate\Http\Request;
    // use Request;
    use Excel;
    //------------------------------
	use DB;
	use CRUDBooster;
	use App\Supplier;
	use App\SupplierApproval;
	use App\ApprovalWorkflowSetting;

	class AdminSuppliersController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "card_id";
			$this->limit = "20";
			$this->orderby = "id,desc";
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
			$this->table = "suppliers";
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
// 			$this->col[] = ["label"=>"Payment Memo","name"=>"payment_memo"];
// 			$this->col[] = ["label"=>"Freight Tax Code","name"=>"freight_tax_code"];
// 			$this->col[] = ["label"=>"Use Supplier Tax Code","name"=>"use_supplier_tax_code"];
// 			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
// 			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
// 			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
// 			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];


//          hided by cris
//              $this->col[] = ["label"=>"Co./Last Name","name"=>"last_name"];
// 			$this->col[] = ["label"=>"First Name","name"=>"first_name","visible"=>false];
// 			$this->col[] = ["label"=>"Card ID","name"=>"card_id"];
// 			$this->col[] = ["label"=>"Card Status","name"=>"card_status"];
// 			$this->col[] = ["label"=>"Currency Code","name"=>"currencies_id","join"=>"currencies,currency_code"];
// 			//1ST
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
// 			//2ND
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
// 			//3RD
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
// 			//4TH
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
// 			//5TH
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
// 			$this->col[] = ["label"=>"Cost Per Hour","name"=>"cost_per_hour","visible"=>false];
// 			$this->col[] = ["label"=>"Terms-Payment is Due","name"=>"terms_payment_is_due","visible"=>false];
// 			$this->col[] = ["label"=>"Discount Days","name"=>"terms_discount_days","visible"=>false];
// 			$this->col[] = ["label"=>"Balance Due Days","name"=>"terms_balance_due_days","visible"=>false];
// 			$this->col[] = ["label"=>"Discount","name"=>"terms_discount","visible"=>false];
// 			$this->col[] = ["label"=>"Tax Code","name"=>"tax_codes_id","join"=>"tax_codes,tax_code"];
// 			$this->col[] = ["label"=>"Credit Limit","name"=>"credit_limit","visible"=>false];
// 			$this->col[] = ["label"=>"Tax ID No.","name"=>"tax_id_no"];
// 			$this->col[] = ["label"=>"Volume Discount","name"=>"volume_discount","visible"=>false];
// 			$this->col[] = ["label"=>"Sales/Purchase Layout","name"=>"sales_purchase_layout"];
// 			$this->col[] = ["label"=>"Account","name"=>"account","visible"=>false];
// 			$this->col[] = ["label"=>"Comment","name"=>"comment","visible"=>false];
// 			$this->col[] = ["label"=>"Shipping Method","name"=>"shipping_method","visible"=>false];
// 			$this->col[] = ["label"=>"Printed Form","name"=>"printed_form","visible"=>false];
// 			$this->col[] = ["label"=>"Freight Tax Code","name"=>"freight_tax_code","join"=>"tax_codes,tax_code"];
// 			$this->col[] = ["label"=>"Use Suppliers Tax Code","name"=>"use_supplier_tax_code"];
// 			$this->col[] = ["label"=>"Payment Memo","name"=>"payment_memo","visible"=>false];
// 			$this->col[] = ["label"=>"Invoice/Purchase Order Delivery","name"=>"invoice_purchase_order_delivery","visible"=>false];
// 			$this->col[] = ["label"=>"Record ID","name"=>"record_id","visible"=>false];
// 			if(CRUDBooster::isSuperadmin()) {
// 				$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
// 				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
// 				$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
// 				$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
// 				# END COLUMNS DO NOT REMOVE THIS LINE
// 			}
                //---added by cris 20200707----
                $this->col[] = ["label"=>"Card ID","name"=>"card_id"];
        			$this->col[] = ["label"=>"Card Status","name"=>"card_status"];
        			$this->col[] = ["label"=>"Vendor","name"=>"last_name","visible"=>true];
        			$this->col[] = ["label"=>"Currency","name"=>"currencies_id", "join"=>"currencies,currency_code"];
        			
        			$this->col[] = ["label"=>"First Name","name"=>"first_name1"];
        			$this->col[] = ["label"=>"Last Name","name"=>"last_name1"];
        			$this->col[] = ["label"=>"Main Phone","name"=>"phone_number1"];
        
        			$this->col[] = ["label"=>"Balance","name"=>"balance","visible"=>false];
        			$this->col[] = ["label"=>"Balance Total","name"=>"balance_total","visible"=>false];
        			$this->col[] = ["label"=>"Balance (PHP)","name"=>"balance_php","visible"=>false];
        			$this->col[] = ["label"=>"Balance Total (PHP)","name"=>"balance_total_php","visible"=>false];
        			$this->col[] = ["label"=>"Terms","name"=>"terms_id","join"=>"terms,terms_description","visible"=>false];
        
                if (CRUDBooster::isSuperadmin()) {
                    $this->col[] = ["label" => "Created By", "name" => "created_by", "join" => "cms_users,name"];
                    $this->col[] = ["label" => "Created Date", "name" => "created_at"];
                    $this->col[] = ["label" => "Updated By", "name" => "updated_by", "join" => "cms_users,name"];
                    $this->col[] = ["label" => "Updated Date", "name" => "updated_at"];
                    # END COLUMNS DO NOT REMOVE THIS LINE
                }
                //-------------------------------------

           
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			
			
			//---added by cris 20200707
            $this->form[] = ['label' => 'Card Status', 'name' => 'card_status', 'type' => 'select', 'validation' => 'required', 'width' => 'col-sm-4', 'dataenum' => 'ACTIVE;NON-ACTIVE'];//, 'help' => 'If Active = N, If Inactive = Y'
            $this->form[] = ['label' => 'Vendor', 'name' => 'last_name', 'type' => 'text', 'validation' => 'required|min:2|max:100', 'width' => 'col-sm-4'];
            if (CRUDBooster::getCurrentMethod() == 'getDetail') {
                $this->form[] = ['label' => 'Card ID', 'name' => 'card_id', 'type' => 'text', 'validation' => 'required|min:6|max:9', 'width' => 'col-sm-4'];
            }
            $this->form[] = ['label' => 'Currency', 'name' => 'currencies_id', 'type' => 'select2', 'validation' => 'required|integer|min:0', 'width' => 'col-sm-4', 'datatable' => 'currencies,currency_code', 'datatable_where' => "status = 'ACTIVE'"];
            // $this->form[] = ['label' => 'Balance', 'name' => 'balance', 'type' => 'number', 'validation' => 'integer|min:0', 'width' => 'col-sm-4'];
            // $this->form[] = ['label' => 'Balance Total', 'name' => 'balance_total', 'type' => 'number', 'validation' => 'integer|min:0', 'width' => 'col-sm-4'];
            // $this->form[] = ['label' => 'Balance (PHP)', 'name' => 'balance_php', 'type' => 'number', 'validation' => 'integer|min:0', 'width' => 'col-sm-4'];
            // $this->form[] = ['label' => 'Balance Total (PHP)', 'name' => 'balance_total_php', 'type' => 'number', 'validation' => 'integer|min:0', 'width' => 'col-sm-4'];
    		$this->form[] = ['label'=>'Company','name'=>'company','type'=>'text','validation'=>'required|min:2|max:100','width'=>'col-sm-4', 'readonly'=>true];
            $this->form[] = ['label'=>'Mr./Ms./...','name'=>'mr_ms','type'=>'select','width'=>'col-sm-4', 'dataenum'=>'MR;MS;MRS'];
            $this->form[] = ['label' => 'First Name', 'name' => 'first_name1', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'M.I.', 'name' => 'middle_name1', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Last Name', 'name' => 'last_name1', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Bill From 1', 'name' => 'bill_from1', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4', 'readonly'=>true];
            $this->form[] = ['label' => 'Bill From 2', 'name' => 'bill_from2', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Bill From 3', 'name' => 'bill_from3', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Bill From 4', 'name' => 'bill_from4', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Bill From 5', 'name' => 'bill_from5', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Ship From 1', 'name' => 'ship_from1', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Ship From 2', 'name' => 'ship_from2', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Ship From 3', 'name' => 'ship_from3', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Ship From 4', 'name' => 'ship_from4', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Ship From 5', 'name' => 'ship_from5', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label'=>'Terms','name'=>'terms_id','type'=>'select2','validation'=>'required','datatable'=>'terms,terms_description','datatable_where'=>'status = "ACTIVE"','width'=>'col-sm-4'];
            $this->form[] = ['label' => 'Primary Contact', 'name' => 'phone_number2', 'type' => 'text', 'validation' => 'required|min:4|max:20', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Job Title', 'name' => 'job_title', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Main Phone', 'name' => 'phone_number1', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Fax', 'name' => 'fax_number', 'type' => 'text', 'validation' => 'min:7|max:20', 'width' => 'col-sm-4'];
            $this->form[] = ['label'=>'Alt Phone','name'=>'phone2_number1','type'=>'number','width'=>'col-sm-4'];
            $this->form[] = ['label' => 'Secondary Contact', 'name' => 'phone_number3', 'type' => 'text', 'width' => 'col-sm-4'];
                //-----added by cris 20200826---
    			$this->form[] = ['label'=>'Minimum Order Value','name'=>'minimum_order_value','type'=>'number','validation'=>'required|min:0.00','width'=>'col-sm-5'];
                //-----------------------------
            //---------------------------
			
			//   hided by cris
// 			$this->form[] = ['label'=>'Last Name','name'=>'last_name','type'=>'text','validation'=>'required|min:2|max:100','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'First Name','name'=>'first_name','type'=>'text','validation'=>'max:100','width'=>'col-sm-4'];
// 			if(CRUDBooster::getCurrentMethod() == 'getDetail'){
// 				$this->form[] = ['label'=>'Card ID','name'=>'card_id','type'=>'text','validation'=>'required|min:6|max:9','width'=>'col-sm-4'];
// 			}
// 			$this->form[] = ['label'=>'Card Status','name'=>'card_status','type'=>'select','validation'=>'required','width'=>'col-sm-4','dataenum'=>'N;Y','help'=>'If Active = N, If Inactive = Y'];
// 			$this->form[] = ['label'=>'Currency','name'=>'currencies_id','type'=>'select2','validation'=>'integer|min:0','width'=>'col-sm-4','datatable'=>'currencies,currency_code','datatable_where'=>"status = 'ACTIVE'"];
// 			$this->form[] = ['label'=>'Address1 Line1','name'=>'address1_line1','type'=>'text','validation'=>'max:100','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Address1 Line2','name'=>'address1_line2','type'=>'text','validation'=>'max:50','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'City','name'=>'cities_id','type'=>'select2','validation'=>'integer|min:0','width'=>'col-sm-4','datatable'=>'cities,city_name','datatable_where'=>"status = 'ACTIVE'"];
// 			$this->form[] = ['label'=>'States','name'=>'states_id','type'=>'select2','validation'=>'integer|min:0','width'=>'col-sm-4','datatable'=>'states,state_name','datatable_where'=>"status = 'ACTIVE'"];
// 			$this->form[] = ['label'=>'Post Code','name'=>'post_code','type'=>'text','validation'=>'min:4|max:20','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Country','name'=>'countries_id','type'=>'select2','validation'=>'integer|min:0','width'=>'col-sm-4','datatable'=>'countries,country_name','datatable_where'=>"status = 'ACTIVE'"];
// 			$this->form[] = ['label'=>'Phone #1','name'=>'phone_number1','type'=>'text','validation'=>'min:4|max:20','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Phone #2','name'=>'phone_number2','type'=>'text','validation'=>'min:4|max:20','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Phone #3','name'=>'phone_number3','type'=>'text','validation'=>'min:4|max:20','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Contact Fax #','name'=>'fax_number','type'=>'text','validation'=>'min:7|max:20','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Contact Email','name'=>'email','type'=>'email','validation'=>'email|unique:suppliers,email','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Contact Name','name'=>'contact_name','type'=>'text','validation'=>'min:2|max:30','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Contact Salutation','name'=>'salutation','type'=>'text','validation'=>'min:2|max:5','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Terms Payment Is Due','name'=>'terms_payment_is_due','type'=>'select','validation'=>'required|integer|min:0','width'=>'col-sm-4','help'=>'If MYOB Format for COD = 1, If MYOB Format on a Day of the Month = 3','dataenum'=>'1;3'];
// 			$this->form[] = ['label'=>'Terms Balance Due Days','name'=>'terms_balance_due_days','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Tax Code','name'=>'tax_codes_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-4','datatable'=>'tax_codes,tax_code','datatable_where'=>"status = 'ACTIVE' and tax_code != 'FRE'"];
// 			$this->form[] = ['label'=>'Tax ID No.','name'=>'tax_id_no','type'=>'text','validation'=>'min:15|max:16','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Sales Purchase Layout','name'=>'sales_purchase_layout','type'=>'select','validation'=>'required','width'=>'col-sm-4','dataenum'=>'I;S','help'=>'If Item = I, If Service = S'];
// 			$this->form[] = ['label'=>'Freight Tax Code','name'=>'freight_tax_code','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-4','datatable'=>'tax_codes,tax_code','datatable_where'=>"status = 'ACTIVE'"];
// 			$this->form[] = ['label'=>'Use Supplier Tax Code','name'=>'use_supplier_tax_code','type'=>'select','validation'=>'required','width'=>'col-sm-4','dataenum'=>'N;Y','help'=>'If Active = N, If Inactive = Y'];
// 			$this->form[] = ['label'=>'Payment Memo','name'=>'payment_memo','type'=>'text','validation'=>'max:50','width'=>'col-sm-4'];
// 			$this->form[] = ['label'=>'Invoice Purchase Order Delivery','name'=>'invoice_purchase_order_delivery','type'=>'select','validation'=>'required','width'=>'col-sm-4','dataenum'=>'P'];
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
            //---added by cris 20201006----
            if (CRUDBooster::getCurrentMethod() == 'getIndex') {
                if(CRUDBooster::isSuperadmin() || CRUDBooster::myPrivilegeName() == "Manager (Purchaser)")
                {
                    $this->index_button[] = [
                    "title" => "Update Vendor",
                    "label" => "Update Vendor",
                    "color" => "success",
                    "icon" => "fa fa-upload", "url" => CRUDBooster::mainpath('update-vendor')
                ];
                }
            }
            //-----------------------------


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
	        $this->load_js[] = asset("js/supplier_master.js");
	        
	        
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
			//$query->where('suppliers.approval_status',1)->orWhere('suppliers.approval_status',3);
		
			$query->where(function($sub_query){
				$create_supplier_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('next_state');
				$update_supplier_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('next_state');

				$sub_query->where('suppliers.approval_status', 	$create_supplier_status);
				$sub_query->orWhere('suppliers.approval_status',$update_supplier_status);
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
			$postdata["encoder_privilege_id"]	=	CRUDBooster::myPrivilegeId();
			$postdata["created_by"]				=	CRUDBooster::myId();
			$postdata["action_type"]			=	"Create";
			$postdata['approval_status']		=	ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('current_state');

			$card_counter = DB::table('code_counters')->where('type', 'SUBMASTER')->value('code_1'); //supplier
			$code = str_pad($card_counter, 6, '0', STR_PAD_LEFT);	

			$postdata['card_id'] = "SUP".$code;

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id){        
			//Your code here
			//supplier counter
			DB::table('code_counters')->where('type', 'SUBMASTER')->increment('code_1');
			$supplier_details = Supplier::where('id',$id)->get()->toArray();
			//Insert data to temporary table
			SupplierApproval::insert($supplier_details);

			$for_approval = SupplierApproval::where('id',$id)->first();
			$approvers = ApprovalWorkflowSetting::where('status','ACTIVE')->where('action_type', 'Create')
							->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->get();

			foreach ($approvers as $approvers_list){
				$approver_privilege_for =	DB::table('cms_privileges')->where('id',$approvers_list->encoder_privilege_id)->first();
				$approver_privilege =		DB::table('cms_privileges')->where('id',$approvers_list->approver_privilege_id)->first();	
			
				if($for_approval->encoder_privilege_id == $approver_privilege_for->id){
					$send_to =	DB::table('cms_users')->where('id_cms_privileges',$approver_privilege->id)->get();
					foreach ($send_to as $send_now){
						$config['content'] = CRUDBooster::myName(). " has created Supplier with Card Id ".$for_approval->card_id." at Supplier Module!";
						$config['to'] = CRUDBooster::adminPath('supplier_approval?q='.$for_approval->card_id);
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
			SupplierApproval::where('id',$id)->update([
				'last_name' 					=> $postdata['last_name'],
				'first_name' 					=> $postdata['first_name'],
				//'card_id' 					=> $postdata['card_id'],
				'card_status' 					=> $postdata['card_status'],
				'currencies_id' 				=> $postdata['currencies_id'],
				'address1_line1' 				=> $postdata['address1_line1'],
				'address1_line2' 				=> $postdata['address1_line2'],
				'cities_id' 					=> $postdata['cities_id'],
				'states_id' 					=> $postdata['states_id'],
				'post_code' 					=> $postdata['post_code'],
				'countries_id' 					=> $postdata['countries_id'],
				'phone_number1'					=> $postdata['phone_number1'],
				'phone_number2'					=> $postdata['phone_number2'],
				'phone_number3'					=> $postdata['phone_number3'],
				'fax_number'					=> $postdata['fax_number'],
				'email'							=> $postdata['email'],
				'contact_name'					=> $postdata['contact_name'],
				'salutation'					=> $postdata['salutation'],
				'terms_payment_is_due' 			=> $postdata['terms_payment_is_due'],
				'terms_balance_due_days' 		=> $postdata['terms_balance_due_days'],
				'tax_codes_id' 					=> $postdata['tax_codes_id'],
				'tax_id_no' 					=> $postdata['tax_id_no'],
				'sales_purchase_layout' 		=> $postdata['sales_purchase_layout'],
				'freight_tax_code' 				=> $postdata['freight_tax_code'],
				'use_supplier_tax_code' 		=> $postdata['use_supplier_tax_code'],
				'payment_memo' 					=> $postdata['payment_memo'],
				//'invoice_purchase_order_delivery' => $postdata['invoice_purchase_order_delivery'],
				'updated_by'					=> CRUDBooster::myId(),
				'encoder_privilege_id'			=> CRUDBooster::myPrivilegeId(),
				'action_type'					=> 'Update',
				'approval_status'				=> ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('current_state')
			]);

			unset($postdata);
			unset($this->arr);
			
			$this->arr["updated_by"] 			= CRUDBooster::myId();
			$this->arr["encoder_privilege_id"] 	= CRUDBooster::myPrivilegeId();
			$this->arr["action_type"] 			= "Update";
			//$this->arr["approval_status"] 		= ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('current_state');
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
			$for_approval = SupplierApproval::where('id',$id)->first();

			$approvers = ApprovalWorkflowSetting::where('status','ACTIVE')->where('action_type', 'Update')
							->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->get();

			foreach ($approvers as $approvers_list){
				$approver_privilege_for =	DB::table('cms_privileges')->where('id',$approvers_list->encoder_privilege_id)->first();
				$approver_privilege =		DB::table('cms_privileges')->where('id',$approvers_list->approver_privilege_id)->first();	
			
				if($for_approval->encoder_privilege_id == $approver_privilege_for->id){
					$send_to =	DB::table('cms_users')->where('id_cms_privileges',$approver_privilege->id)->get();
					foreach ($send_to as $send_now){
						$config['content'] = CRUDBooster::myName(). " has edited Supplier with Card Id ".$for_approval->card_id." at Supplier Module!";
						$config['to'] = CRUDBooster::adminPath('supplier_approval?q='.$for_approval->card_id);
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
			$item_info = SupplierApproval::find($id);
			$supplier_update_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('current_state');
			
			if($item_info->approval_status == $supplier_update_status){
				CRUDBooster::redirect(CRUDBooster::mainpath(""),"You're not allowed to edit pending items for approval.","warning");
			}

			return parent::getEdit($id);
		}
		
		//----added by cris 20201006
	
        public function updateVendor() {
            $this->cbLoader();
           
            $data['page_title'] = 'Update Vendor';
    
            // send to front-end view
            $this->cbView("upload.update_vendor", $data);
        }
    
        public function vendorUpdate(Request $request) {
            set_time_limit(-1);
            $file = $request->file('import_file');
                
    // 			return response()->json(['errors' => $file]);
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
                $lastname = DB::table('suppliers')->select('last_name')->where('last_name', '!=', null)->get()->toArray();
                // dd(count($tasteless_code));
                for($i = 0; $i < count($lastname); $i++)
                {
                     array_push($in_db,$lastname[$i]->last_name);
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
                        if($value->vendor ==''){
                            
                            $cnt_fail++; 
                        }
                        else{
    
                            // $tax_code_id = 0;
                            // if($value->sales_tax_code == "TAX")
                            // {
                            //     $tax_code_id = 1;
                            // }else{
                            //     $tax_code_id = 2;
                            // }
                            // $account = strtoupper($value->account);
                            // $cogs_account = strtoupper($value->cogs_account);
                            // $asset_account = strtoupper($value->asset_account);
                            // $uom = strtoupper($value->um);
                            // $uom_set = strtoupper($value->um_set);
                        
                            $currency_id = DB::table('currencies')->where('currency_code',$value->currency)->select('id')->first();
                            // $cogs_account_id = DB::table('cogs_accounts')->where('group_description',$cogs_account)->select('id')->first();
                            // $asset_account_id = DB::table('asset_accounts')->where('group_description',$asset_account)->select('id')->first();
                            // $uom_id = DB::table('uoms')->where('uom_description',$uom)->select('id')->first();
                            // $uom_set_id = DB::table('uoms_set')->where('uom_description',$uom_set)->select('id')->first();
                            // $preferred_vendor_id = DB::table('suppliers')->where('last_name',$value->preferred_vendor)->select('id')->first();
                            // $group_id = DB::table('groups')->where('group_description',$value->group)->select('id')->first();
                            // $packagings_id = DB::table('packagings')->where('packaging_code',$value->packaging_uom)->select('id')->first();
    
                            if(!in_array($value->vendor,$in_db))// if new tasteless_code
                            {
                                array_push($new_item, $value->vendor);
    
                            }
                            
                            $data = [
                                'action_type'               => "Update",
                                // 'last_name'                 => $value->vendor,
                                'currencies_id'             => $currency_id->id,
                                'balance'                   => intval($value->balance),
                                'balance_total'             => intval($value->balance_total),
                                'balance_php'               => intval($value->balance_php),
                                'balance_total_php'         => intval($value->balance_total_php),
                                'company'                   => $value->vendor,
                                'mr_ms'                     => $value['mr.ms....'],
                                'first_name1'               => $value->first_name,
                                'middle_name1'              => $value['m.i.'],
                                'last_name1'                => $value->last_name,
                                'bill_from1'               => $value->vendor,
                                'bill_from2'               => $value->bill_from_2,
                                'bill_from3'               => $value->bill_from_3,
                                'bill_from4'               => $value->bill_from_4,
                                'bill_from5'               => $value->bill_from_5,
                                'ship_from1'               => $value->ship_from_1,
                                'ship_from2'               => $value->ship_from_2,
                                'ship_from3'               => $value->ship_from_3,
                                'ship_from4'               => $value->ship_from_4,
                                'ship_from5'               => $value->ship_from_5,
                                'phone_number2'             => intval($value->primary_contact),
                                'job_title'                 => $value->job_title,
                                'phone_number1'             => intval($value->main_phone),
                                'fax_number'                => intval($value->fax),
                                'phone2_number1'            => intval($value['alt._phone']),
                                'phone_number3'             => intval($value->secondary_contact),
                                'updated_by'                => CRUDBooster::myId(),
                                'updated_at'                =>  date('Y-m-d H:i:s')
                                    
                                ];
                                
                            DB::beginTransaction();			
                            try {
                                // if(!in_array($value->item,$in_db))
                                // {
                                //     //  DB::table('item_masters')->insert($new_data);
                                // }
                                // dd($data);
                                DB::table('suppliers')->where('last_name', $value->vendor)->update($data);
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
                            
                            Excel::create('new-vendor' . date("Ymd") . '-' . date("h.i.sa"), function ($excel) use ($new_item) {
                                $excel->sheet('new_vendor', function ($sheet) use ($new_item) {
    
                                    $cnt_item = count($new_item);
                                    for($i = 0; $i < $cnt_item; $i++)
                                    {
                                        $sheet->prependRow($i+1, [$new_item[$i]]);
                                        // $sheet->row($i+2, function ($row) {
                                            // $row->setBackground('#FFFF00');
                                            // $row->setAlignment('center');
                                        // });
                                        
                                    }
                                });
                            })->export('xlsx');
                            
                            CRUDBooster::redirect(CRUDBooster::mainpath(), 'Upload success!. New vendors found: '. $str. ' please manual add these vendors.', 'success');
                            // return response()->json(['success' => trans("crudbooster.alert_upload_price_success"),
                            // 'New items found: ' => $new_item]);
                        }else{
                            CRUDBooster::redirect(CRUDBooster::mainpath(), 'Update vendors success!', 'success');
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
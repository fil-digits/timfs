<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\Supplier;
	use App\SupplierApproval;
	use App\ApprovalWorkflowSetting;

	class AdminSuppliersApprovalController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "card_id";
			$this->limit = "20";
			$this->orderby =  "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = true;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "supplier_approvals";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
            $this->col[] = ["label" => "Action Type", "name" => "action_type"];
            $this->col[] = ["label" => "Approval Status", "name" => "approval_status"];
            $this->col[] = ["label" => "Vendor", "name" => "last_name", "visible" => true];
            $this->col[] = ["label" => "Card ID", "name" => "card_id"];
            $this->col[] = ["label" => "Currency", "name" => "currencies_id", "join" => "currencies,currency_code"];
            $this->col[] = ["label" => "Balance", "name" => "balance"];
            $this->col[] = ["label" => "Balance Total", "name" => "balance_total"];
            $this->col[] = ["label" => "Balance (PHP)", "name" => "balance_php"];
            $this->col[] = ["label" => "Balance Total (PHP)", "name" => "balance_total_php"];
            $this->col[] = ["label" => "Company", "name" => "company"];
            $this->col[] = ["label" => "Mr./Ms./...", "name" => "mr_ms"];
            $this->col[] = ["label" => "First Name", "name" => "first_name1"];
            $this->col[] = ["label" => "MI", "name" => "middle_name1"];
            $this->col[] = ["label" => "Last Name", "name" => "last_name1"];
            $this->col[] = ["label" => "Bill From 1", "name" => "bill_from1"];
            $this->col[] = ["label" => "Bill From 2", "name" => "bill_from2"];
            $this->col[] = ["label" => "Bill From 3", "name" => "bill_from3"];
            $this->col[] = ["label" => "Bill From 4", "name" => "bill_from4"];
            $this->col[] = ["label" => "Bill From 5", "name" => "bill_from5"];
            $this->col[] = ["label" => "Ship From 1", "name" => "ship_from1"];
            $this->col[] = ["label" => "Ship From 2", "name" => "bill_from2"];
            $this->col[] = ["label" => "Ship From 3", "name" => "bill_from3"];
            $this->col[] = ["label" => "Ship From 4", "name" => "bill_from4"];
            $this->col[] = ["label" => "Ship From 5", "name" => "bill_from5"];
            $this->col[] = ["label" => "Primary Contact", "name" => "phone_number2"];
            $this->col[] = ["label" => "Job Title", "name" => "job_title"];
            $this->col[] = ["label" => "Main Phone", "name" => "phone_number1"];
            $this->col[] = ["label" => "Fax", "name" => "fax_number"];
            $this->col[] = ["label" => "Alt Phone", "name" => "phone2_number1"];
            $this->col[] = ["label" => "Secondary Contact", "name" => "phone_number3"];
            $this->col[] = ["label" => "Created By", "name" => "created_by", "join" => "cms_users,name"];
            $this->col[] = ["label" => "Created Date", "name" => "created_at"];
            $this->col[] = ["label" => "Updated By", "name" => "updated_by", "join" => "cms_users,name"];
            $this->col[] = ["label" => "Updated Date", "name" => "updated_at"];
            # END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
            $this->form[] = ['label'=>'Card ID','name'=>'card_id','type'=>'text','validation'=>'required|min:6|max:9','width'=>'col-sm-4'];
    		$this->form[] = ['label' => 'Card Status', 'name' => 'card_status', 'type' => 'select', 'validation' => 'required', 'width' => 'col-sm-4', 'dataenum' => 'ACTIVE;NON-ACTIVE'];//, 'help' => 'If Active = N, If Inactive = Y'
            $this->form[] = ['label' => 'Currency', 'name' => 'currencies_id', 'type' => 'select2', 'validation' => 'required|integer|min:0', 'width' => 'col-sm-4', 'datatable' => 'currencies,currency_code', 'datatable_where' => "status = 'ACTIVE'"];
            $this->form[] = ['label' => 'Balance', 'name' => 'balance', 'type' => 'number', 'validation' => 'integer|min:0', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Balance Total', 'name' => 'balance_total', 'type' => 'number', 'validation' => 'integer|min:0', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Balance (PHP)', 'name' => 'balance_php', 'type' => 'number', 'validation' => 'integer|min:0', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Balance Total (PHP)', 'name' => 'balance_total_php', 'type' => 'number', 'validation' => 'integer|min:0', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Company', 'name' => 'company', 'type' => 'text', 'validation' => 'required|min:2|max:100', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Mr./Ms./...', 'name' => 'mr_ms', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'First Name', 'name' => 'first_name1', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'M.I.', 'name' => 'middle_name1', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Last Name', 'name' => 'last_name1', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Bill From 1', 'name' => 'bill_from1', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-4'];
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
            $this->form[] = ['label' => 'Alt Phone', 'name' => 'phone2_number1', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label' => 'Secondary Contact', 'name' => 'phone_number3', 'type' => 'text', 'width' => 'col-sm-4'];
            $this->form[] = ['label'=>'Minimum Order Value','name'=>'minimum_order_value','type'=>'number','validation'=>'required|min:0.00','width'=>'col-sm-4'];
            

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
			if(CRUDBooster::isUpdate() && (in_array(CRUDBooster::myPrivilegeName(),['Administrator','Supervisor (Purchaser)','Supervisor (Accounting)','Manager (Accounting)']) || CRUDBooster::isSuperadmin() )) {
	        	$this->button_selected[] = ['label'=>'APPROVED','icon'=>'fa fa-check','name'=>'APPROVED'];
				$this->button_selected[] = ['label'=>'REJECT','icon'=>'fa fa-times','name'=>'REJECT'];
			}
	                
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
	        if($button_name == 'APPROVED') {

				foreach ($id_selected as $key=>$value){

					$item_info = SupplierApproval::where('id',$value)->first();

					switch ($item_info->action_type){
						//create workflow
						case 'Create' :
							$module_id = DB::table('cms_moduls')->where('controller','AdminSuppliersController')->value('id');

							$approver_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')
											->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
											->where('status','ACTIVE')->where('action_type', 'Create')->first();

							//update status to approved

							SupplierApproval::where('id',$item_info['id'])->update([
									'approval_status'=> $approver_checker->next_state,
									'approved_at' => date('Y-m-d H:i:s'), 
									'approved_by' => CRUDBooster::myId()
							]);

							//get encoder id
							Supplier::where('id',$item_info['id'])->update([
								'card_status' 					=> $item_info['card_status'],
								'last_name' 					=> $item_info['last_name'],
								'currencies_id' 				=> $item_info['currencies_id'],
								'balance'						=> $item_info['balance'],
								'balance_total'					=> $item_info['balance_total'],
								'balance_php'					=> $item_info['balance_php'],
								'balance_total_php'				=> $item_info['balance_total_php'],
								'company'						=> $item_info['company'],
								'mr_ms'							=> $item_info['mr_ms'],
								'first_name1'					=> $item_info['first_name1'],
								'middle_name1'					=> $item_info['middle_name1'],
								'last_name1'					=> $item_info['last_name1'],
								'bill_from1'					=> $item_info['bill_from1'],
								'bill_from2'					=> $item_info['bill_from2'],
								'bill_from3'					=> $item_info['bill_from3'],
								'bill_from4'					=> $item_info['bill_from4'],
								'bill_from5'					=> $item_info['bill_from5'],
								'ship_from1'					=> $item_info['ship_from1'],
								'ship_from2'					=> $item_info['ship_from2'],
								'ship_from3'					=> $item_info['ship_from3'],
								'ship_from4'					=> $item_info['ship_from4'],
								'ship_from5'					=> $item_info['ship_from5'],
								'phone_number2'					=> $item_info['phone_number2'],
								'job_title'						=> $item_info['job_title'],
								'phone_number1'					=> $item_info['phone_number1'],
								'fax_number'					=> $item_info['fax_number'],
								'phone2_number1'				=> $item_info['phone2_number1'],
								'phone_number3'					=> $item_info['phone_number3'],
								'approval_status'				=> $approver_checker->next_state,
								'approved_at' 					=> date('Y-m-d H:i:s'), 
								'approved_by' 					=> CRUDBooster::myId()
							]);

							//send notification to encoder
							
							//--added by cris 20200729-----
    						$config['content'] = CRUDBooster::myName(). " has approved your item ".$item_info->last_name1." at Supplier For Approval Module!";
    						//-------------------------------
							
							//  hided by cris
							$config['to'] = CRUDBooster::adminPath('suppliers/detail/'.$item_info->id);

							if($item_info->action_type == "Create"){
								$config['id_cms_users'] = [$item_info->created_by];
							}
							else{
								$config['id_cms_users'] = [$item_info->updated_by];
							}
						
							CRUDBooster::sendNotification($config);
						break;

						//update workflow
						case 'Update' :
							$module_id = DB::table('cms_moduls')->where('controller','AdminSuppliersController')->value('id');

							$approver_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')
											->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
											->where('status','ACTIVE')->where('action_type', 'Update')->first();

							//udate status to approved

							SupplierApproval::where('id',$item_info['id'])->update([
									'approval_status'=> $approver_checker->next_state,
									'approved_at' => date('Y-m-d H:i:s'), 
									'approved_by' => CRUDBooster::myId()
							]);

							//get encoder id
							Supplier::where('id',$item_info['id'])->update([
								'card_status' 					=> $item_info['card_status'],
								'last_name' 					=> $item_info['last_name'],
								'currencies_id' 				=> $item_info['currencies_id'],
								'balance'						=> $item_info['balance'],
								'balance_total'					=> $item_info['balance_total'],
								'balance_php'					=> $item_info['balance_php'],
								'balance_total_php'				=> $item_info['balance_total_php'],
								'company'						=> $item_info['company'],
								'mr_ms'							=> $item_info['mr_ms'],
								'first_name1'					=> $item_info['first_name1'],
								'middle_name1'					=> $item_info['middle_name1'],
								'last_name1'					=> $item_info['last_name1'],
								'bill_from1'					=> $item_info['bill_from1'],
								'bill_from2'					=> $item_info['bill_from2'],
								'bill_from3'					=> $item_info['bill_from3'],
								'bill_from4'					=> $item_info['bill_from4'],
								'bill_from5'					=> $item_info['bill_from5'],
								'ship_from1'					=> $item_info['ship_from1'],
								'ship_from2'					=> $item_info['ship_from2'],
								'ship_from3'					=> $item_info['ship_from3'],
								'ship_from4'					=> $item_info['ship_from4'],
								'ship_from5'					=> $item_info['ship_from5'],
								'phone_number2'					=> $item_info['phone_number2'],
								'job_title'						=> $item_info['job_title'],
								'phone_number1'					=> $item_info['phone_number1'],
								'fax_number'					=> $item_info['fax_number'],
								'phone2_number1'				=> $item_info['phone2_number1'],
								'phone_number3'					=> $item_info['phone_number3'],
								'approval_status'				=> $approver_checker->next_state,
								'approved_at' 					=> date('Y-m-d H:i:s'), 
								'approved_by' 					=> CRUDBooster::myId()
							]);	
							
							//send notification to encoder
    					    $config['content'] = CRUDBooster::myName(). " has approved your item ".$item_info->last_name1." at Supplier For Approval Module!";
							
							$config['to'] = CRUDBooster::adminPath('suppliers/detail/'.$item_info->id);

							if($item_info->action_type == "Create"){
								$config['id_cms_users'] = [$item_info->created_by];
							}
							else{
								$config['id_cms_users'] = [$item_info->updated_by];
							}
						
							CRUDBooster::sendNotification($config);
						break;
						
						default:
						break;	
					}
				}
				CRUDBooster::redirect(CRUDBooster::mainpath(""),"The item has been approved successfully !","success");
			}elseif($button_name == 'REJECT') {
				//update status to rejected
		    	// Supplier::whereIn('id',$id_selected)->update([
				// 		'approval_status'=> 2
				// 	]);
				SupplierApproval::whereIn('id',$id_selected)->update([
						'approval_status'=> 404
					]);

		    	foreach ($id_selected as $key=>$value) {
					//get encoder id
					$item_info = SupplierApproval::where('id',$value)->first();

					//send notification to encoder
    				$config['content'] = CRUDBooster::myName(). " has rejected your item ".$item_info->last_name1." at Supplier For Approval Module!";
					$config['to'] = CRUDBooster::adminPath('supplier_approval/edit/'.$item_info->id);
					
					if($item_info->action_type == "Create"){
						$config['id_cms_users'] = [$item_info->created_by];
					}
					else{
						$config['id_cms_users'] = [$item_info->updated_by];
					}
					
					CRUDBooster::sendNotification($config);
				}
				CRUDBooster::redirect(CRUDBooster::mainpath(""),"The item has been rejected successfully !","warning");
	        }    
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
	        if(CRUDBooster::isSuperadmin() || CRUDBooster::myPrivilegeName() == "Administrator"){
				$query->where(function($sub_query){
					$module_id = DB::table('cms_moduls')->where('controller','AdminSuppliersController')->value('id');

					$create_supplier_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('cms_moduls_id', 'LIKE', '%' . $module_id  . '%')->value('current_state');
					$update_supplier_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('cms_moduls_id', 'LIKE', '%' . $module_id  . '%')->value('current_state');

					$sub_query->where('supplier_approvals.approval_status',	  $create_supplier_status);
					$sub_query->orWhere('supplier_approvals.approval_status', $update_supplier_status);
					$sub_query->orWhere('supplier_approvals.approval_status', 404);
				});
			}else{
				$query->where(function($sub_query){

					$module_id = DB::table('cms_moduls')->where('controller','AdminSuppliersController')->value('id');
					$approver_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')
										->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
										->where('status','ACTIVE')->first();

					switch($approver_checker){
						case NULL:
							$sub_query->where('supplier_approvals.approval_status',	  404)->where('supplier_approvals.created_by',CRUDBooster::myId());
							$sub_query->orWhere('supplier_approvals.approval_status', 404)->where('supplier_approvals.updated_by',CRUDBooster::myId());
						break;

						default:
								$approver_get = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')
									->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
									->where('status','ACTIVE')->get();
						
 								foreach($approver_get as $approver){
									 $sub_query->where('supplier_approvals.approval_status',	 $approver->current_state)->where('supplier_approvals.encoder_privilege_id',	$approver->encoder_privilege_id);
									 $sub_query->orWhere('supplier_approvals.approval_status', $approver->current_state)->where('supplier_approvals.encoder_privilege_id',	$approver->encoder_privilege_id);
 								}
						break;
					}
				});
			}    
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
			//Your code here
			if($column_index == 3){
				switch ($column_value) {
					case 1:
						$column_value = '<span stye="display: block;" class="label label-info">Approved</span><br>';
						break;
					case 404:
						$column_value = '<span stye="display: block;" class="label label-danger">Rejected</span><br>';
						break;
					default:
						$column_value = '<span stye="display: block;" class="label label-warning">Pending</span><br>';
						break;
				}
			}
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
			$postdata["created_by"]=CRUDBooster::myId();
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
			//$for_approval = SupplierApproval::where('id',$id)->first();
			$module_id = DB::table('cms_moduls')->where('controller','AdminSuppliersController')->value('id');

			$postdata["updated_by"]				=	CRUDBooster::myId();
			$postdata["encoder_privilege_id"]	=	CRUDBooster::myPrivilegeId();
			$postdata['approval_status']		= 	ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');	
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
			$module_id = DB::table('cms_moduls')->where('controller','AdminSuppliersController')->value('id');
			$approvers = ApprovalWorkflowSetting::where('status','ACTIVE')->where('action_type', 'Update')
							->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->get();

			foreach ($approvers as $approvers_list){
				$approver_privilege_for =	DB::table('cms_privileges')->where('id',$approvers_list->encoder_privilege_id)->first();
				$approver_privilege =		DB::table('cms_privileges')->where('id',$approvers_list->approver_privilege_id)->first();	
			
				if($for_approval->encoder_privilege_id == $approver_privilege_for->id){
					$send_to =	DB::table('cms_users')->where('id_cms_privileges',$approver_privilege->id)->get();
					foreach ($send_to as $send_now){
						$config['content'] = CRUDBooster::myName(). " has edited Supplier with Card Id ".$for_approval->card_id." at Supplier For Approval Module!";
						$config['to'] = CRUDBooster::adminPath('supplier_approval?q='.$for_approval->card_id);
						$config['id_cms_users'] = [$send_now->id];
						CRUDBooster::sendNotification($config);	
					}
				}
				
			}

			CRUDBooster::redirect(CRUDBooster::mainpath(),"Your item has been resend and pending for approval.","info");
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

		public function getEdit($id){
            $module_id = DB::table('cms_moduls')->where('controller','AdminSuppliersController')->value('id');

			$item_info = SupplierApproval::find($id);

			$create_update_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('approver_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');
			$supplier_update_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('approver_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');
			$created_update_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('next_state');
			
			if ($item_info->approval_status == $create_update_status || $item_info->approval_status == $supplier_update_status){
				CRUDBooster::redirect(CRUDBooster::mainpath(""),"You're not allowed to edit pending items for approval.","warning");
			}
			if ($item_info->approval_status == $created_update_status ) {
				CRUDBooster::redirect(CRUDBooster::mainpath(""),"You're not allowed to edit approved items in approval window.","warning");
			}

			return parent::getEdit($id);
		}
	}
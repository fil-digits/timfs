<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\ChartAccount;
	use App\ChartAccountApproval;
	use App\ApprovalWorkflowSetting;

	class AdminChartAccountForApprovalController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}
		
	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "account_name";
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
			$this->table = "chart_account_approvals";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Action Type","name"=>"action_type"];
			$this->col[] = ["label"=>"Approval Status","name"=>"approval_status"];
			$this->col[] = ["label"=>"Account Number","name"=>"account_number"];
			$this->col[] = ["label"=>"Account Name","name"=>"account_name"];
			$this->col[] = ["label"=>"Header for Account List","name"=>"header"];
			$this->col[] = ["label"=>"Account Type","name"=>"account_type"];
			$this->col[] = ["label"=>"Tax Code","name"=>"tax_codes_id","join"=>"tax_codes,tax_code"];
			$this->col[] = ["label"=>"Currency","name"=>"currencies_id","join"=>"currencies,currency_code"];
			$this->col[] = ["label"=>"Inactive Account","name"=>"inactive_account"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Header for Account List','name'=>'header','type'=>'select','validation'=>'required','width'=>'col-sm-4','dataenum'=>'H;D','help'=>'If Header = H, If Detailed = D'];
			$this->form[] = ['label'=>'Account Number','name'=>'account_number','type'=>'text','validation'=>'required|max:10','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Account Type','name'=>'account_type','type'=>'select','validation'=>'required','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Account Name','name'=>'account_name','type'=>'text','validation'=>'required|max:50','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Tax Code','name'=>'tax_codes_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-4','datatable'=>'tax_codes,tax_code','datatable_where'=>"status = 'ACTIVE' and tax_code != 'FRE'"];
			$this->form[] = ['label'=>'Currency','name'=>'currencies_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-4','datatable'=>'currencies,currency_code','datatable_where'=>"status = 'ACTIVE'"];
			$this->form[] = ['label'=>'Inactive Account','name'=>'inactive_account','type'=>'select','validation'=>'required','width'=>'col-sm-4','dataenum'=>'N;Y','help'=>'If Active = N, If Inactive = Y'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Action Type","name"=>"action_type","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Account Number","name"=>"account_number","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Account Name","name"=>"account_name","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Header","name"=>"header","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Balance","name"=>"balance","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Account Type","name"=>"account_type","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Last Cheque Number","name"=>"last_cheque_number","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Tax Codes Id","name"=>"tax_codes_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"tax_codes,id"];
			//$this->form[] = ["label"=>"Currencies Id","name"=>"currencies_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"currencies,id"];
			//$this->form[] = ["label"=>"Exchange Account","name"=>"exchange_account","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Inactive Account","name"=>"inactive_account","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"B Account Number","name"=>"b_account_number","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"B Account Name","name"=>"b_account_name","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Description","name"=>"description","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Classification","name"=>"classification","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Sub Total Header","name"=>"sub_total_header","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Accounts","name"=>"accounts","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Approval Status","name"=>"approval_status","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Approved By","name"=>"approved_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Approved At","name"=>"approved_at","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Created By","name"=>"created_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Updated By","name"=>"updated_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			# OLD END FORM

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
			if(CRUDBooster::isUpdate() && CRUDBooster::myPrivilegeName() == 'Administrator' || CRUDBooster::isSuperadmin() || CRUDBooster::myPrivilegeName() == 'Manager (Accounting)') {
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
			$module_id = DB::table('cms_moduls')->where('controller','AdminChartAccountsController')->value('id');

			$approver_checker = ApprovalWorkflowSetting::
					where('cms_moduls_id', 'LIKE', '%' . $module_id  . '%')
			   		->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
					   ->where('status','ACTIVE')->first();			

			if($approver_checker == NULL && CRUDBooster::myPrivilegeName() != 'Super Administrator'){
					if(CRUDBooster::myPrivilegeName() != 'Administrator'){
						$this->load_js[] = asset("js/chart_account.js");
					}
			}
	        
	        
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
				//udate status to approved

				foreach ($id_selected as $key=>$value){

					$item_info = ChartAccountApproval::where('id',$value)->first();

					switch ($item_info->action_type){

						//create workflow
						case 'Create' :
							$module_id = DB::table('cms_moduls')->where('controller','AdminChartAccountsController')->value('id');

							$approver_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')
													->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
													->where('status','ACTIVE')->where('action_type', 'Create')->first();
							
							ChartAccountApproval::where('id',$item_info['id'])->update([
								'approval_status'=> 	$approver_checker->next_state,
								'approved_at' =>		date('Y-m-d H:i:s'), 
								'approved_by' => 		CRUDBooster::myId()
							]);

							ChartAccount::where('id',$item_info['id'])->update([
								'header' 						=> $item_info['header'],
								'account_number' 				=> $item_info['account_number'],
								//'card_id' 					=> $item_info['card_id'],
								'account_type' 					=> $item_info['account_type'],
								'account_name' 					=> $item_info['account_name'],
								'tax_codes_id' 					=> $item_info['tax_codes_id'],
								'currencies_id' 				=> $item_info['currencies_id'],
								'inactive_account' 				=> $item_info['inactive_account'],
								'approval_status'				=> $approver_checker->next_state,
								'approved_at' 					=> date('Y-m-d H:i:s'), 
								'approved_by' 					=> CRUDBooster::myId()
							]);

							//send notification to encoder
							$config['content'] = CRUDBooster::myName(). " has approved your item ".$item_info->account_name." at Chart of Account For Approval Module!";
							$config['to'] = CRUDBooster::adminPath('chart_accounts/detail/'.$item_info->id);
									
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
							$module_id = DB::table('cms_moduls')->where('controller','AdminChartAccountsController')->value('id');

							$approver_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')
												->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
												->where('status','ACTIVE')->where('action_type', 'Update')->first();

							ChartAccountApproval::where('id',$item_info['id'])->update([
								'approval_status'=> 	$approver_checker->next_state,
								'approved_at' =>		date('Y-m-d H:i:s'), 
								'approved_by' => 		CRUDBooster::myId()
							]);

							ChartAccount::where('id',$item_info['id'])->update([
								'header' 						=> $item_info['header'],
								'account_number' 				=> $item_info['account_number'],
								//'card_id' 					=> $item_info['card_id'],
								'account_type' 					=> $item_info['account_type'],
								'account_name' 					=> $item_info['account_name'],
								'tax_codes_id' 					=> $item_info['tax_codes_id'],
								'currencies_id' 				=> $item_info['currencies_id'],
								'inactive_account' 				=> $item_info['inactive_account'],
								'approval_status'				=> $approver_checker->next_state,
								'approved_at' 					=> date('Y-m-d H:i:s'), 
								'approved_by' 					=> CRUDBooster::myId()
							]);

							//send notification to encoder
							$config['content'] = CRUDBooster::myName(). " has approved your item ".$item_info->account_name." at Chart of Account For Approval Module!";
							$config['to'] = CRUDBooster::adminPath('chart_accounts/detail/'.$item_info->id);
									
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
				ChartAccountApproval::whereIn('id',$id_selected)->update([
						'approval_status'=> 404
					]);

		    	foreach ($id_selected as $key=>$value){
					//get encoder id
					$item_info = ChartAccountApproval::where('id',$value)->first();

					//send notification to encoder
					$config['content'] = CRUDBooster::myName(). " has rejected your item ".$item_info->account_name." at Chart of Account For Approval Module!";
					$config['to'] = CRUDBooster::adminPath('chart_account_for_approval/edit/'.$item_info->id);
					
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
					$module_id = DB::table('cms_moduls')->where('controller','AdminChartAccountsController')->value('id');

					$create_coa_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('cms_moduls_id', 'LIKE', '%' . $module_id. '%')->value('current_state');
					$update_coa_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');				
					
					$sub_query->where('chart_account_approvals.approval_status',	$create_coa_status);
					$sub_query->orWhere('chart_account_approvals.approval_status',	$update_coa_status);
					$sub_query->orWhere('chart_account_approvals.approval_status', 404);
				});

			}else{
				$query->where(function($sub_query){
					$module_id = DB::table('cms_moduls')->where('controller','AdminChartAccountsController')->value('id');

					$approver_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')
										->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
										->where('status','ACTIVE')->first();	
										
					switch($approver_checker){
						case NULL:
							$sub_query->where('chart_account_approvals.approval_status',	  404)->where('chart_account_approvals.created_by',CRUDBooster::myId());
							$sub_query->orWhere('chart_account_approvals.approval_status', 404)->where('chart_account_approvals.updated_by',CRUDBooster::myId());
						break;

						default:
								$approver_get = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')
									->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
									->where('status','ACTIVE')->get();
						
								 foreach($approver_get as $approver){
									 $sub_query->where('chart_account_approvals.approval_status',	 $approver->current_state)->where('chart_account_approvals.encoder_privilege_id',	$approver->encoder_privilege_id);
									 $sub_query->orWhere('chart_account_approvals.approval_status', $approver->current_state)->where('chart_account_approvals.encoder_privilege_id',	$approver->encoder_privilege_id);
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
			$module_id = DB::table('cms_moduls')->where('controller','AdminChartAccountsController')->value('id');

			$postdata["updated_by"]				=	CRUDBooster::myId();
			$postdata["encoder_privilege_id"]	=	CRUDBooster::myPrivilegeId();
			$postdata['approval_status']		=	ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');		
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
			$module_id = DB::table('cms_moduls')->where('controller','AdminChartAccountsController')->value('id');
			
			$for_approval = ChartAccountApproval::where('id',$id)->first();
			$approvers = ApprovalWorkflowSetting::where('status','ACTIVE')->where('action_type', 'Update')
							->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->get();

			foreach ($approvers as $approvers_list){
				$approver_privilege_for =	DB::table('cms_privileges')->where('id',$approvers_list->encoder_privilege_id)->first();
				$approver_privilege =		DB::table('cms_privileges')->where('id',$approvers_list->approver_privilege_id)->first();	
			
				if($for_approval->encoder_privilege_id == $approver_privilege_for->id){
					$send_to =	DB::table('cms_users')->where('id_cms_privileges',$approver_privilege->id)->get();
					foreach ($send_to as $send_now){
						$config['content'] = CRUDBooster::myName(). " has edited Chart of Account with Account Number ".$for_approval->account_number." at Chart of Account Module!";
						$config['to'] = CRUDBooster::adminPath('chart_account_for_approval?q='.$for_approval->account_number);
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

		public function getEdit($id) {
		    $module_id = DB::table('cms_moduls')->where('controller','AdminChartAccountsController')->value('id');
		    	
			$item_info = ChartAccountApproval::find($id);

			$create_update_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('approver_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');
			$supplier_update_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('approver_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');
			$created_update_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('next_state');

			if ($item_info->approval_status == $create_update_status  || $item_info->approval_status == $supplier_update_status) {
				CRUDBooster::redirect(CRUDBooster::mainpath(""),"You're not allowed to edit pending items for approval.","warning");
			}
			if ($item_info->approval_status == $created_update_status) {
				CRUDBooster::redirect(CRUDBooster::mainpath(""),"You're not allowed to edit approved items in approval window.","warning");
			}

			return parent::getEdit($id);
		}
	}
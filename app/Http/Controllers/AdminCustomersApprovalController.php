<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\Customer;
	use App\CustomerApproval;
	use App\Channel;
	use App\ApprovalWorkflowSetting;

	class AdminCustomersApprovalController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}
		
	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "customer_name";
			$this->limit = "20";
			$this->orderby = "customer_name,asc";
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
			$this->table = "customer_approvals";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Action Type","name"=>"action_type"];
			$this->col[] = ["label"=>"Approval Status","name"=>"approval_status"];
			$this->col[] = ["label"=>"Channel","name"=>"channels_id","join"=>"channels,channel_description"];
			$this->col[] = ["label"=>"Trade Name","name"=>"trade_name"];
			$this->col[] = ["label"=>"Mall","name"=>"mall"];
			$this->col[] = ["label"=>"Branch","name"=>"branch"];
			$this->col[] = ["label"=>"First Name","name"=>"first_name"];
			$this->col[] = ["label"=>"Last Name","name"=>"last_name"];
			$this->col[] = ["label"=>"Customer Name","name"=>"customer_name"];
			$this->col[] = ["label"=>"Concept Group","name"=>"concept_groups_id","join"=>"concept_groups,concept_group_name"];
			$this->col[] = ["label"=>"Segmentation","name"=>"segmentations_id","join"=>"segmentations,segment_column_description"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Channel','name'=>'channels_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-4','datatable'=>'channels,channel_description'];
			$this->form[] = ['label'=>'Trade Name','name'=>'trade_name','type'=>'text','validation'=>'max:100','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Mall','name'=>'mall','type'=>'text','validation'=>'max:60','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Branch','name'=>'branch','type'=>'text','validation'=>'max:60','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'First Name','name'=>'first_name','type'=>'text','validation'=>'max:30','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Last Name','name'=>'last_name','type'=>'text','validation'=>'max:30','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Customer Name','name'=>'customer_name','type'=>'text','validation'=>'required|min:5|max:150','width'=>'col-sm-4','readonly'=>true];
			$this->form[] = ['label'=>'Concept Group','name'=>'concept_groups_id','type'=>'select2','validation'=>'integer|min:0','width'=>'col-sm-4','datatable'=>'concept_groups,concept_group_name'];
			$this->form[] = ['label'=>'Segmentation','name'=>'segmentations_id','type'=>'select2','validation'=>'integer|min:0','width'=>'col-sm-4','datatable'=>'segmentations,segment_column_description'];
			if(in_array(CRUDBooster::getCurrentMethod(),['getEdit','postEditSave','getDetail'])) {
				$this->form[] = ['label'=>'Status','name'=>'status','type'=>'select','validation'=>'required','width'=>'col-sm-4','dataenum'=>'ACTIVE;INACTIVE'];
			}
			if(CRUDBooster::getCurrentMethod() == 'getDetail'){
				$this->form[] = ["label"=>"Created By","name"=>"created_by",'type'=>'select',"datatable"=>"cms_users,name"];
				$this->form[] = ['label'=>'Created Date','name'=>'created_at', 'type'=>'datetime'];
				$this->form[] = ["label"=>"Updated By","name"=>"updated_by",'type'=>'select',"datatable"=>"cms_users,name"];
				$this->form[] = ['label'=>'Updated Date','name'=>'updated_at', 'type'=>'datetime'];
			}
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
			if(CRUDBooster::isUpdate() && CRUDBooster::myPrivilegeName() == 'Administrator' || CRUDBooster::isSuperadmin()|| CRUDBooster::myPrivilegeName() == 'Supervisor (Purchaser)' || CRUDBooster::myPrivilegeName() == 'Supervisor (Accounting)') {
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
					
					$item_info = CustomerApproval::where('id',$value)->first();

					switch ($item_info->action_type){

						case 'Create' :
						$module_id = DB::table('cms_moduls')->where('controller','AdminCustomersController')->value('id');

						$approver_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')
							->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
							->where('status','ACTIVE')->where('action_type', 'Create')->first();

							CustomerApproval::where('id',$item_info['id'])->update([
								'approval_status'=> $approver_checker->next_state,
								'approved_at' => date('Y-m-d H:i:s'), 
								'approved_by' => CRUDBooster::myId()
							]);

							Customer::where('id',$item_info['id'])->update([
									"channels_id" 		=> $item_info["channels_id"],
									"trade_name" 		=> $item_info["trade_name"],
									"mall" 				=> $item_info["mall"],
									"branch" 			=> $item_info["branch"],
									"first_name" 		=> $item_info["first_name"],
									"last_name" 		=> $item_info["last_name"],
									"customer_name" 	=> $item_info["customer_name"],
									"concept_groups_id" => $item_info["concept_groups_id"],
									"segmentations_id" 	=> $item_info["segmentations_id"],
									"status" 			=> $item_info["status"],
									'approval_status'	=>  $approver_checker->next_state,
									'approved_at' 		=> date('Y-m-d H:i:s'), 
									'approved_by' 		=> CRUDBooster::myId()
								]);
			
								//send notification to encoder
								$config['content'] = CRUDBooster::myName(). " has approved your item ".$item_info->customer_name." at Customer For Approval Module!";
								$config['to'] = CRUDBooster::adminPath('customers/detail/'.$item_info->id);
								
								if($item_info->action_type == "Create"){
									$config['id_cms_users'] = [$item_info->created_by];
								}
								else{
									$config['id_cms_users'] = [$item_info->updated_by];
								}
			
								CRUDBooster::sendNotification($config);
						break;

						case 'Update' :
						$module_id = DB::table('cms_moduls')->where('controller','AdminCustomersController')->value('id');

						$approver_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')
											->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
											->where('status','ACTIVE')->where('action_type', 'Update')->first();

						CustomerApproval::where('id',$item_info['id'])->update([
							'approval_status'=> $approver_checker->next_state,
							'approved_at' => date('Y-m-d H:i:s'), 
							'approved_by' => CRUDBooster::myId()
						]);

						Customer::where('id',$item_info['id'])->update([
								"channels_id" 		=> $item_info["channels_id"],
								"trade_name" 		=> $item_info["trade_name"],
								"mall" 				=> $item_info["mall"],
								"branch" 			=> $item_info["branch"],
								"first_name" 		=> $item_info["first_name"],
								"last_name" 		=> $item_info["last_name"],
								"customer_name" 	=> $item_info["customer_name"],
								"concept_groups_id" => $item_info["concept_groups_id"],
								"segmentations_id" 	=> $item_info["segmentations_id"],
								"status" 			=> $item_info["status"],
								'approval_status'	=>  $approver_checker->next_state,
								'approved_at' 		=> date('Y-m-d H:i:s'), 
								'approved_by' 		=> CRUDBooster::myId()
							]);
		
							//send notification to encoder
							$config['content'] = CRUDBooster::myName(). " has approved your item ".$item_info->customer_name." at Customer For Approval Module!";
							$config['to'] = CRUDBooster::adminPath('customers/detail/'.$item_info->id);
							
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
			}
			elseif($button_name == 'REJECT') {
				//update status to rejected
		    	// Customer::whereIn('id',$id_selected)->update([
				// 		'approval_status'=> 2
				// 	]);
				CustomerApproval::whereIn('id',$id_selected)->update([
						'approval_status'=> 404
					]);

		    	foreach ($id_selected as $key=>$value) {
					//get encoder id
					$item_info = CustomerApproval::where('id',$value)->first();

					//send notification to encoder
					$config['content'] = CRUDBooster::myName(). " has rejected your item ".$item_info->customer_name." at Customer For Approval Module!";
					$config['to'] = CRUDBooster::adminPath('customer_approval/edit/'.$item_info->id);
					
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
					$module_id = DB::table('cms_moduls')->where('controller','AdminCustomersController')->value('id');

					$create_customer_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('cms_moduls_id', 'LIKE', '%' . $module_id  . '%')->value('current_state');
					$update_customer_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('cms_moduls_id', 'LIKE', '%' . $module_id  . '%')->value('current_state');
					
					$sub_query->where('customer_approvals.approval_status',    $create_customer_status);
					$sub_query->orWhere('customer_approvals.approval_status',  $update_customer_status);
					$sub_query->orWhere('customer_approvals.approval_status', 404);
				});
			}else{
				$query->where(function($sub_query){

					$module_id = DB::table('cms_moduls')->where('controller','AdminCustomersController')->value('id');
					$approver_checker = ApprovalWorkflowSetting::
										 where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')
										->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
										->where('status','ACTIVE')->first();	
										
							switch($approver_checker){
								case NULL:
									$sub_query->where('customer_approvals.approval_status',	  404)->where('customer_approvals.created_by',CRUDBooster::myId());
									$sub_query->orWhere('customer_approvals.approval_status', 404)->where('customer_approvals.updated_by',CRUDBooster::myId());
								break;
		
								default:
										$approver_get = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')
											->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
											->where('status','ACTIVE')->get();
								
										 foreach($approver_get as $approver){
											 $sub_query->where('customer_approvals.approval_status',	 $approver->current_state)->where('customer_approvals.encoder_privilege_id',	$approver->encoder_privilege_id);
											 $sub_query->orWhere('customer_approvals.approval_status', $approver->current_state)->where('customer_approvals.encoder_privilege_id',	$approver->encoder_privilege_id);
										 }
								break;
							}										
					/*if($approver_checker != NULL ){
						$sub_query->where('customer_approvals.approval_status',0)->where('customer_approvals.encoder_privilege_id',$approver_checker->encoder_privilege_id);
						$sub_query->orWhere('customer_approvals.approval_status',3)->where('customer_approvals.encoder_privilege_id',$approver_checker->encoder_privilege_id);
					}else{
						$sub_query->where('customer_approvals.approval_status',2)->where('customer_approvals.created_by',CRUDBooster::myId());
						$sub_query->orWhere('customer_approvals.approval_status',2)->where('customer_approvals.updated_by',CRUDBooster::myId());
					}*/
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
			$module_id = DB::table('cms_moduls')->where('controller','AdminCustomersController')->value('id');

			$postdata["updated_by"]				=	CRUDBooster::myId();
			$postdata['approval_status']		=	ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');	
			$postdata["encoder_privilege_id"]	=	CRUDBooster::myPrivilegeId();

			//get channel code
			$channel_code = $this->getChannelCode($postdata['channels_id']);
			
			if(!empty($postdata['trade_name']) && !empty($postdata['mall']) && !empty($postdata['branch'])){
				switch ($postdata['mall']) {
					case 'N/A': case 'n/a':
						$mall = "";
						break;
					
					default:
						$mall = ".".$postdata['mall'];
						break;
				}
	
				switch ($postdata['branch']) {
					case 'N/A': case 'n/a':
						$branch = "";
						break;
					
					default:
						$branch = ".".$postdata['branch'];
						break;
				}
				//final customer name

				//if(!empty($postdata['customer_name'])){
					$postdata['customer_name'] = $postdata['trade_name'].$mall.$branch.".".$channel_code[0];
				//}
				
			}
			else{
				$postdata['customer_name'] = $postdata['first_name'].".".$postdata['last_name'].".".$channel_code[0];
			}
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
			$module_id = DB::table('cms_moduls')->where('controller','AdminCustomersController')->value('id');

			$for_approval = CustomerApproval::where('id',$id)->first();
			$approvers = ApprovalWorkflowSetting::where('status','ACTIVE')->where('action_type', 'Update')
							->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->get();

			foreach ($approvers as $approvers_list){
				$approver_privilege_for =	DB::table('cms_privileges')->where('id',$approvers_list->encoder_privilege_id)->first();
				$approver_privilege =		DB::table('cms_privileges')->where('id',$approvers_list->approver_privilege_id)->first();	
			
				if($for_approval->encoder_privilege_id == $approver_privilege_for->id){
					$send_to =	DB::table('cms_users')->where('id_cms_privileges',$approver_privilege->id)->get();
					foreach ($send_to as $send_now){
						$config['content'] = CRUDBooster::myName(). " has edited Customer with Customer Name ".$for_approval->customer_name." at Customer For Approval Module!";
						$config['to'] = CRUDBooster::adminPath('customer_approval?q='.$for_approval->customer_name);
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
		    $module_id = DB::table('cms_moduls')->where('controller','AdminCustomersController')->value('id');
		    
			$item_info = CustomerApproval::find($id);

			$create_update_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('approver_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');
			$supplier_update_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('approver_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');
			$created_update_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('next_state');

			if ($item_info->approval_status == $create_update_status || $item_info->approval_status == $supplier_update_status) {
				CRUDBooster::redirect(CRUDBooster::mainpath(""),"You're not allowed to edit pending items for approval.","warning");
			}
			if ($item_info->approval_status == $created_update_status) {
				CRUDBooster::redirect(CRUDBooster::mainpath(""),"You're not allowed to edit approved items in approval window.","warning");
			}
			return parent::getEdit($id);
		}

		public function getChannelCode($channel_id) {
			return Channel::where('channels.id', $channel_id)->pluck('channels.channel_code');
		}
	}
<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\MenuItem;
	use App\MenuItemApproval;
	use App\ApprovalWorkflowSetting;
	use Illuminate\Support\Facades\Input;

	class AdminMenuItemApprovalsController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}
		
	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
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
			$this->table = "menu_item_approvals";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Action Type","name"=>"action_type"];
			$this->col[] = ["label"=>"Approval Status","name"=>"approval_status"];
			$this->col[] = ["label"=>"Tasteless Menu Code","name"=>"tasteless_menu_code"];
			$this->col[] = ["label"=>"Menu Item Description","name"=>"menu_item_description"];
			$this->col[] = ["label"=>"Menu Category","name"=>"menu_categories_id","join"=>"menu_categories,category_description"];
			$this->col[] = ["label"=>"Menu Subcategory","name"=>"menu_subcategories_id","join"=>"menu_subcategories,subcategory_description"];
			$this->col[] = ["label"=>"Tax Code","name"=>"tax_codes_id","join"=>"tax_codes,tax_code"];
			$this->col[] = ["label"=>"Menu Cost Price","name"=>"menu_cost_price"];
			$this->col[] = ["label"=>"Menu Selling Price","name"=>"menu_selling_price"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Menu Item Description','name'=>'menu_item_description','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Menu Category','name'=>'menu_categories_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-4','datatable'=>'menu_categories,category_description','datatable_where'=>"status='ACTIVE'"];
			$this->form[] = ['label'=>'Menu Subcategory','name'=>'menu_subcategories_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-4','datatable'=>'menu_subcategories,subcategory_description','datatable_where'=>"status='ACTIVE'"];
			$this->form[] = ['label'=>'Tax Code','name'=>'tax_codes_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-4','datatable'=>'tax_codes,tax_code','datatable_where'=>"status='ACTIVE'"];
			$this->form[] = ['label'=>'Menu Cost Price','name'=>'menu_cost_price','type'=>'number','validation'=>'required|min:0','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Menu Selling Price','name'=>'menu_selling_price','type'=>'number','validation'=>'required|min:0','width'=>'col-sm-4'];
			if(in_array(CRUDBooster::getCurrentMethod(),['getDetail'])){
			    foreach($segmentation_data as $datas){
					$this->form[] = ['label'=>'Segmentation'." ".$datas->segment_column_description,'name'=>$datas->segment_column_name,'type'=>'checkbox-custom','width'=>'col-sm-4'];
				}
			}else{
			    $this->form[] = ['label'=>'Segmentation','name'=>'segmentation','type'=>'checkbox-menu','validation'=>'required|integer|min:0','width'=>'col-sm-6',
					'datatable'=>'segmentations,segment_column_description,segment_column_name','datatable_where'=>"status='ACTIVE'"];
			}
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Action Type","name"=>"action_type","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Tasteless Menu Code","name"=>"tasteless_menu_code","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Menu Item Description","name"=>"menu_item_description","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Menu Categories Id","name"=>"menu_categories_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"menu_categories,id"];
			//$this->form[] = ["label"=>"Menu Subcategories Id","name"=>"menu_subcategories_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"menu_subcategories,id"];
			//$this->form[] = ["label"=>"Tax Codes Id","name"=>"tax_codes_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"tax_codes,id"];
			//$this->form[] = ["label"=>"Status","name"=>"status","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Menu Cost Price","name"=>"menu_cost_price","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Menu Selling Price","name"=>"menu_selling_price","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation","name"=>"segmentation","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Segmentation Any","name"=>"segmentation_any","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Bbd","name"=>"segmentation_bbd","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Eyb","name"=>"segmentation_eyb","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Cbl","name"=>"segmentation_cbl","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Com","name"=>"segmentation_com","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Fmr","name"=>"segmentation_fmr","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Fwb","name"=>"segmentation_fwb","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Fzb","name"=>"segmentation_fzb","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Hmk","name"=>"segmentation_hmk","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Htw","name"=>"segmentation_htw","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Kkd","name"=>"segmentation_kkd","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Lps","name"=>"segmentation_lps","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Lbs","name"=>"segmentation_lbs","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Mtd","name"=>"segmentation_mtd","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Ppd","name"=>"segmentation_ppd","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Pze","name"=>"segmentation_pze","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Psn","name"=>"segmentation_psn","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Ppr","name"=>"segmentation_ppr","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Rcf","name"=>"segmentation_rcf","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Scb","name"=>"segmentation_scb","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Sch","name"=>"segmentation_sch","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Sdh","name"=>"segmentation_sdh","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Smk","name"=>"segmentation_smk","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Twu","name"=>"segmentation_twu","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Tbf","name"=>"segmentation_tbf","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Tgd","name"=>"segmentation_tgd","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Wkd","name"=>"segmentation_wkd","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Wks","name"=>"segmentation_wks","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation Wmn","name"=>"segmentation_wmn","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 1","name"=>"segmentation_1","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 2","name"=>"segmentation_2","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 3","name"=>"segmentation_3","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 4","name"=>"segmentation_4","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 5","name"=>"segmentation_5","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 6","name"=>"segmentation_6","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 7","name"=>"segmentation_7","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 8","name"=>"segmentation_8","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 9","name"=>"segmentation_9","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 10","name"=>"segmentation_10","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 11","name"=>"segmentation_11","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 12","name"=>"segmentation_12","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 13","name"=>"segmentation_13","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 14","name"=>"segmentation_14","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 15","name"=>"segmentation_15","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 16","name"=>"segmentation_16","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 17","name"=>"segmentation_17","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 18","name"=>"segmentation_18","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 19","name"=>"segmentation_19","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 20","name"=>"segmentation_20","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Segmentation 21","name"=>"segmentation_21","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Approval Status","name"=>"approval_status","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Approved By","name"=>"approved_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Approved At","name"=>"approved_at","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Encoder Privilege Id","name"=>"encoder_privilege_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"encoder_privilege,id"];
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
			if(CRUDBooster::isUpdate() && CRUDBooster::myPrivilegeName() == 'Administrator' || CRUDBooster::myPrivilegeName() == 'Supervisor (Purchaser)' || CRUDBooster::myPrivilegeName() == 'Supervisor (Accounting)' || CRUDBooster::isSuperadmin()) {
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
            if($button_name == 'APPROVED') {
                
            foreach ($id_selected as $key=>$value){

					$item_info = MenuItemApproval::where('id',$value)->first();

							$module_id = DB::table('cms_moduls')->where('controller','AdminMenuItemsController')->value('id');

							$approver_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')
											->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
											->where('status','ACTIVE')->where('action_type', 'Create')->first();

	    	                //get category number
	    	                $category_num = DB::table('menu_categories')->where('id',$item_info['menu_categories_id'])->value('id');
	    	                //get subcat code
	    	                $subcategory_codes = DB::table('menu_subcategories')->where('id',$item_info['menu_subcategories_id'])->value('subcategory_code');
	    	                //pass category number to menu counter
	    	                //$cnt = DB::table('tbl_menu_counter')->where('id', 1)->value('code_'.$category_num.'');

	    	                $cnt = DB::table('menu_item_approvals')->where('menu_subcategories_id',$item_info['menu_subcategories_id'])->count();
	    	                $cnt++;
	    	                
	    	                if($cnt<10){
				                    $menu_code = $category_num.''.$subcategory_codes.'0000'.$cnt;
			                }elseif($cnt>=10 && $cnt<100){
				                    $menu_code = $category_num.''.$subcategory_codes.'000'.$cnt;
			                }elseif($cnt>=100 && $cnt<1000){
				                    $menu_code = $category_num.''.$subcategory_codes.'00'.$cnt;
			                }elseif($cnt>=1000 && $cnt<10000){
				                    $menu_code = $category_num.''.$subcategory_codes.'0'.$cnt;
			                }elseif($cnt>=10000 && $cnt<100000){
				                    $menu_code = $category_num.''.$subcategory_codes.''.$cnt;
			                }	
			                
							//udate status to approved

							MenuItemApproval::where('id',$item_info['id'])->update([
							        'tasteless_menu_code' => $menu_code,
									'approval_status'=> $approver_checker->next_state,
									'approved_at' => date('Y-m-d H:i:s'), 
									'approved_by' => CRUDBooster::myId()
							]);

							//get encoder id
							MenuItem::where('id',$item_info['id'])->update([
								'tasteless_menu_code' 			=> $menu_code,
								'menu_item_description' 		=> $item_info['menu_item_description'],
								//'card_id' 					=> $item_info['card_id'],
								'menu_categories_id' 			=> $item_info['menu_categories_id'],
								'menu_subcategories_id' 		=> $item_info['menu_subcategories_id'],
								'tax_codes_id' 				    => $item_info['tax_codes_id'],
								'status' 				        => $item_info['status'],
								'menu_cost_price' 				=> $item_info['menu_cost_price'],
								'menu_selling_price' 			=> $item_info['menu_selling_price'],
								'segmentation' 					=> $item_info['segmentation'],
								'segmentation_any' 				=> $item_info['segmentation_any'],
								'segmentation_bbd'				=> $item_info['segmentation_bbd'],
								'segmentation_eyb'				=> $item_info['segmentation_eyb'],
								'segmentation_cbl'				=> $item_info['segmentation_cbl'],
								'segmentation_com'				=> $item_info['segmentation_com'],
								'segmentation_fmr'				=> $item_info['segmentation_fmr'],
								'segmentation_fwb'				=> $item_info['segmentation_fwb'],
								'segmentation_fzb'				=> $item_info['segmentation_fzb'],
								'segmentation_hmk' 			    => $item_info['segmentation_hmk'],
								'segmentation_htw' 		        => $item_info['segmentation_htw'],
								'segmentation_kkd' 				=> $item_info['segmentation_kkd'],
								'segmentation_lps' 				=> $item_info['segmentation_lps'],
								'segmentation_lbs' 		        => $item_info['segmentation_lbs'],
								'segmentation_mtd' 				=> $item_info['segmentation_mtd'],
								'segmentation_ppd' 		        => $item_info['segmentation_ppd'],
								'segmentation_pze' 				=> $item_info['segmentation_pze'],
								'segmentation_psn' 				=> $item_info['segmentation_psn'],
								'segmentation_ppr' 				=> $item_info['segmentation_ppr'],
								'segmentation_rcf' 				=> $item_info['segmentation_rcf'],
								'segmentation_scb' 				=> $item_info['segmentation_scb'],
								'segmentation_sch' 				=> $item_info['segmentation_sch'],
								'segmentation_sdh' 				=> $item_info['segmentation_sdh'],
								'segmentation_smk' 				=> $item_info['segmentation_smk'],
								'segmentation_twu' 				=> $item_info['segmentation_twu'],
								'segmentation_tbf' 				=> $item_info['segmentation_tbf'],
								'segmentation_tgd' 				=> $item_info['segmentation_tgd'],
								'segmentation_wkd' 				=> $item_info['segmentation_wkd'],
								'segmentation_wks' 				=> $item_info['segmentation_wks'],
								'segmentation_wmn' 				=> $item_info['segmentation_wmn'],
								'segmentation_1' 				=> $item_info['segmentation_1'],
								'segmentation_2' 				=> $item_info['segmentation_2'],
								'segmentation_3' 				=> $item_info['segmentation_3'],
								'segmentation_4' 				=> $item_info['segmentation_4'],
								'segmentation_5' 				=> $item_info['segmentation_5'],
								'segmentation_6' 				=> $item_info['segmentation_6'],
								'segmentation_7' 				=> $item_info['segmentation_7'],
								'segmentation_8' 				=> $item_info['segmentation_8'],
								'segmentation_9' 				=> $item_info['segmentation_9'],
								'segmentation_10' 				=> $item_info['segmentation_10'],
								'segmentation_11' 				=> $item_info['segmentation_11'],
								'segmentation_12' 				=> $item_info['segmentation_12'],
								'segmentation_13' 				=> $item_info['segmentation_13'],
								'segmentation_14' 				=> $item_info['segmentation_14'],
								'segmentation_15' 				=> $item_info['segmentation_15'],
								'segmentation_16' 				=> $item_info['segmentation_16'],
								'segmentation_17' 				=> $item_info['segmentation_17'],
								'segmentation_18' 				=> $item_info['segmentation_18'],
								'segmentation_19' 				=> $item_info['segmentation_19'],
								'segmentation_20' 				=> $item_info['segmentation_20'],
								'segmentation_21' 				=> $item_info['segmentation_21'],
								'approval_status'				=> $approver_checker->next_state,
								'approved_at' 					=> date('Y-m-d H:i:s'),
								'approved_by' 					=> CRUDBooster::myId()
							]);

							//send notification to encoder
							$config['content'] = CRUDBooster::myName(). " has approved your item ".$item_info->menu_item_description." at Menu Items For Approval Module!";
							$config['to'] = CRUDBooster::adminPath('menu_items/detail/'.$item_info->id);

							if($item_info->action_type == "Create"){
								$config['id_cms_users'] = [$item_info->created_by];
							}
							else{
								$config['id_cms_users'] = [$item_info->updated_by];
							}
						
							CRUDBooster::sendNotification($config);
							
				}
				CRUDBooster::redirect(CRUDBooster::mainpath(""),"The item has been approved successfully !","success");
				
            }elseif($button_name == 'REJECT'){
                	MenuItemApproval::whereIn('id',$id_selected)->update([
						'approval_status'=> 404
					]);

		    	foreach ($id_selected as $key=>$value) {
					//get encoder id
					$item_info = MenuItemApproval::where('id',$value)->first();

					//send notification to encoder
					$config['content'] = CRUDBooster::myName(). " has rejected your item ".$item_info->menu_item_description." at Menu Items For Approval Module!";
					$config['to'] = CRUDBooster::adminPath('menu_item_approvals/edit/'.$item_info->id);
					
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
					$module_id = DB::table('cms_moduls')->where('controller','AdminMenuItemsController')->value('id');

					$create_supplier_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('cms_moduls_id', 'LIKE', '%' . $module_id  . '%')->value('current_state');

					//$update_supplier_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('cms_moduls_id', 'LIKE', '%' . $module_id  . '%')->value('current_state');

					$sub_query->where('menu_item_approvals.approval_status',$create_supplier_status);
					$sub_query->orWhere('menu_item_approvals.approval_status',$update_supplier_status);
					$sub_query->orWhere('menu_item_approvals.approval_status', 404);
				});
			}else{
				$query->where(function($sub_query){

					$module_id = DB::table('cms_moduls')->where('controller','AdminMenuItemsController')->value('id');
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
	        $module_id = DB::table('cms_moduls')->where('controller','AdminMenuItemsController')->value('id');
	        $sku_legend = 			Input::all();
			$segmentation_datas = DB::table('segmentations')->where('status','ACTIVE')->get();

			foreach($segmentation_datas as $segment){
				$segment_search = $sku_legend[$segment->segment_column_name];
				$postdata[$segment->segment_column_name] = $segment_search;
			}
			
	        $postdata['approval_status'] = 	ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');
			$postdata["updated_by"]=CRUDBooster::myId();
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
	        $module_id = DB::table('cms_moduls')->where('controller','AdminMenuItemsController')->value('id');
			
			$for_approval = MenuItemApproval::where('id',$id)->first();
			$approvers = ApprovalWorkflowSetting::where('status','ACTIVE')->where('action_type', 'Create')
							->where('cms_moduls_id', 'LIKE', '%' . $module_id   . '%')->get();

			foreach ($approvers as $approvers_list){
				$approver_privilege_for =	DB::table('cms_privileges')->where('id',$approvers_list->encoder_privilege_id)->first();
				$approver_privilege =		DB::table('cms_privileges')->where('id',$approvers_list->approver_privilege_id)->first();	
			
				if($for_approval->encoder_privilege_id == $approver_privilege_for->id){
					$send_to =	DB::table('cms_users')->where('id_cms_privileges',$approver_privilege->id)->get();
					foreach ($send_to as $send_now){
						$config['content'] = CRUDBooster::myName(). " has edited Menu Item with Menu Item Description".$for_approval->menu_item_description." at Menu Item Module!";
						$config['to'] = CRUDBooster::adminPath('menu_item_approvals?q='.$for_approval->card_id);
						$config['id_cms_users'] = [$send_now->id];
						CRUDBooster::sendNotification($config);	
					}
				}
				
			}
			CRUDBooster::redirect(CRUDBooster::mainpath(),"Your item has been created and pending for approval.","info");

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
	        $module_id = DB::table('cms_moduls')->where('controller','AdminMenuItemsController')->value('id');
	        
	        $item_info = MenuItemApproval::find($id);
	        
	        $create_update_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('approver_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');
            
	        if ($item_info->approval_status == $create_update_status){
				CRUDBooster::redirect(CRUDBooster::mainpath(""),"You're not allowed to edit pending items for approval.","warning");
			}
			
	        return parent::getEdit($id);
	    }

	}
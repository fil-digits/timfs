<?php namespace App\Http\Controllers;

	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use App\MenuItem;
	use App\MenuItemApproval;
	use App\ApprovalWorkflowSetting;
	use App\Exports\ExcelTemplate;
	use App\Exports\MenuItemsExport;
	use App\Imports\MenuItemsImport;
	use App\MenuChoiceGroup;
	use App\MenuOldCodeMaster;
	use App\MenuPriceMaster;
	use App\MenuSegmentation;
	use Illuminate\Support\Facades\Input;
    use Maatwebsite\Excel\HeadingRowImport;
    use Maatwebsite\Excel\Imports\HeadingRowFormatter;
	use Maatwebsite\Excel\Facades\Excel;

	class AdminMenuItemsController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}
	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "tasteless_menu_code";
			$this->limit = "20";
			$this->orderby = "tasteless_menu_code,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "menu_items";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			$old_item_codes = MenuOldCodeMaster::where('status','ACTIVE')->orderBy('menu_old_code_column_description','ASC')->get();
			$prices = MenuPriceMaster::where('status','ACTIVE')->orderBy('menu_price_column_description','ASC')->get();
			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];

			$this->col[] = ["label"=>"Tasteless Menu Code","name"=>"tasteless_menu_code"];
			foreach($old_item_codes as $old_code){
				$this->col[] = ["label"=>ucwords(strtolower($old_code->menu_old_code_column_description)),"name"=>$old_code->menu_old_code_column_name];
			}
			$this->col[] = ["label"=>"POS Old Item Description","name"=>"pos_old_item_description"];
			$this->col[] = ["label"=>"Menu Item Description","name"=>"menu_item_description"];
			$this->col[] = ["label"=>"Menu Category","name"=>"menu_categories_id","join"=>"menu_categories,category_description"];
			$this->col[] = ["label"=>"Menu Subcategory","name"=>"menu_subcategories_id","join"=>"menu_subcategories,subcategory_description"];
			$this->col[] = ["label"=>"Menu Product Type","name"=>"menu_product_types_id","join"=>"menu_product_types,menu_product_type_description"];
			$this->col[] = ["label"=>"Menu Type","name"=>"menu_types_id","join"=>"menu_types,menu_type_description"];
			
			foreach($prices as $price){
				$this->col[] = ["label"=>ucwords(strtolower($price->menu_price_column_description)),"name"=>$price->menu_price_column_name];
			}
            $this->col[] = ["label"=>"Original Concept","name"=>"original_concept"];;
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			if(in_array(CRUDBooster::getCurrentMethod(),['getEdit','postEditSave'])){
			    $this->form[] = ['label'=>'Tasteless Menu Code','name'=>'tasteless_menu_code','type'=>'text','readonly'=>true,'width'=>'col-sm-4'];
			}
			$this->form[] = ['label'=>'Menu Item Description','name'=>'menu_item_description','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Menu Category','name'=>'menu_categories_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-4','datatable'=>'menu_categories,category_description','datatable_where'=>"status='ACTIVE'"];
// 			$this->form[] = ['label'=>'Menu Subcategory','name'=>'menu_subcategories_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-4','datatable'=>'menu_subcategories,subcategory_description','datatable_where'=>"status='ACTIVE'"];
// 			$this->form[] = ['label'=>'Tax Code','name'=>'tax_codes_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-4','datatable'=>'tax_codes,tax_code','datatable_where'=>"status='ACTIVE'"];
// 			$this->form[] = ['label'=>'Menu Cost Price','name'=>'menu_cost_price','type'=>'number','validation'=>'required|min:0','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Menu Selling Price','name'=>'menu_selling_price','type'=>'number','validation'=>'required|min:0','width'=>'col-sm-4'];
			if(in_array(CRUDBooster::getCurrentMethod(),['getEdit','postEditSave','getDetail'])){
				$this->form[] = ['label'=>'Status','name'=>'status','type'=>'select','validation'=>'required','width'=>'col-sm-4','dataenum'=>'ACTIVE;INACTIVE'];
			}
			if(in_array(CRUDBooster::getCurrentMethod(),['getDetail'])){
			    foreach($segmentation_data as $datas){
					$this->form[] = ['label'=>'Segmentation'." ".$datas->menu_segment_column_description,'name'=>$datas->menu_segment_column_name,'type'=>'checkbox-custom','width'=>'col-sm-4'];
				}
			}else{
				$this->form[] = ['label'=>'Segmentation','name'=>'segmentation','type'=>'checkbox-menu','width'=>'col-sm-6',
					'datatable'=>'menu_segmentations,menu_segment_column_description,menu_segment_column_name','datatable_where'=>"status='ACTIVE'"];
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
            if(CRUDBooster::getCurrentMethod() == 'getIndex') {
                if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(),["Purchasing"])){
                    $this->index_button[] = [
                        "title"=>"Upload Menu Items",
                        "label"=>"Upload Menu Items",
                        "icon"=>"fa fa-upload",
                        "color"=>"success",
                        "url"=>route('menu-items.view')];
                }
				$this->index_button[] = ['label'=>'Export Menu Items','url'=>"javascript:showMenuItemExport()",'icon'=>'fa fa-download'];
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
            $this->script_js = "
				function showMenuItemExport() {
					$('#modal-menu-item-export').modal('show');
				}
			";

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
	        $this->post_index_html = "
			<div class='modal fade' tabindex='-1' role='dialog' id='modal-menu-item-export'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button class='close' aria-label='Close' type='button' data-dismiss='modal'>
								<span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'><i class='fa fa-download'></i> Export Menu Items</h4>
						</div>

						<form method='post' target='_blank' action=".CRUDBooster::mainpath("export").">
                        <input type='hidden' name='_token' value=".csrf_token().">
                        ".CRUDBooster::getUrlParameters()."
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label>File Name</label>
                                <input type='text' name='filename' class='form-control' required value='Export ".CRUDBooster::getCurrentModule()->name ." - ".date('Y-m-d H:i:s')."'/>
                            </div>
						</div>
						<div class='modal-footer' align='right'>
                            <button class='btn btn-default' type='button' data-dismiss='modal'>Close</button>
                            <button class='btn btn-primary btn-submit' type='submit'>Submit</button>
                        </div>
                    </form>
					</div>
				</div>
			</div>
			";
	        
	        
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
	       $query->where(function($sub_query){
				$create_item_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('next_state');
				
				$sub_query->where('menu_items.approval_status',	$create_item_status);
				
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
	        //Your code here.
	        
	        $sku_legend = 			Input::all();
			$segmentation_datas = DB::table('segmentations')->where('status','ACTIVE')->get();

			foreach($segmentation_datas as $segment){
				$segment_search = $sku_legend[$segment->segment_column_name];
				$postdata[$segment->segment_column_name] = $segment_search;
			}
			
			
			$postdata["encoder_privilege_id"]		=	CRUDBooster::myPrivilegeId();
            $postdata["created_by"]					=	CRUDBooster::myId();
			$postdata["action_type"]				=	"Create";
			$postdata['approval_status']			= 	ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->orWhere('approver_privilege_id', CRUDBooster::myPrivilegeId())->value('current_state');
	
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        

			$menuitem_details = MenuItem::where('id',$id)->get()->toArray();
			MenuItemApproval::insert($menuitem_details);
			
			$for_approval = MenuItemApproval::where('id',$id)->first();
			$approvers = ApprovalWorkflowSetting::where('status','ACTIVE')->where('action_type', 'Create')
							->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->get();

			foreach ($approvers as $approvers_list){
				$approver_privilege_for =	DB::table('cms_privileges')->where('id',$approvers_list->encoder_privilege_id)->first();
				$approver_privilege =		DB::table('cms_privileges')->where('id',$approvers_list->approver_privilege_id)->first();	
			
				if($for_approval->encoder_privilege_id == $approver_privilege_for->id){
					$send_to =	DB::table('cms_users')->where('id_cms_privileges',$approver_privilege->id)->get();
					foreach ($send_to as $send_now){
						$config['content'] = CRUDBooster::myName(). " has created Menu Item with Menu Item Description".$for_approval->menu_item_description." at Menu Item Module!";
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
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here
	        $sku_legend = Input::all();
			$segmentation_datas = DB::table('segmentations')->where('status','ACTIVE')->get();

			foreach($segmentation_datas as $segment){
				$segment_search = $sku_legend[$segment->segment_column_name];
				$postdata[$segment->segment_column_name] = $segment_search;
			}
			
			//$postdata["encoder_privilege_id"]		=	CRUDBooster::myPrivilegeId();
			$postdata["action_type"]				=	"Update";
			$postdata['approval_status']			= 	ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->orWhere('approver_privilege_id', CRUDBooster::myPrivilegeId())->value('next_state');
			$postdata["updated_by"]                 =   CRUDBooster::myId();
	    
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
	        $item_info = MenuItem::where('id',$id)->first();
	        
            MenuItemApproval::where('id',$item_info['id'])->update([
					'tasteless_menu_code' 			=> $item_info['tasteless_menu_code'],
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
					'approval_status'				=> $item_info['approval_status'],
					'action_type'				    => $item_info['action_type'],
					'updated_by' 					=> $item_info['updated_by'],
					'updated_at' 					=> $item_info['updated_at']
			]);
			
			CRUDBooster::redirect(CRUDBooster::mainpath(),"Your item has been edited and pending for approval.","info");
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
	        
	        $create_update_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->orWhere('approver_privilege_id', CRUDBooster::myPrivilegeId())->value('current_state');

	        if ($item_info->approval_status == $create_update_status){
				CRUDBooster::redirect(CRUDBooster::mainpath(""),"You're not allowed to edit pending items for approval.","warning");
			}
			
	        return parent::getEdit($id);
	    }
	    
	    public function uploadView(){
	        if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }
            
            $data = [];
            $data['page_title'] = 'Upload Menu Items';
            $data['uploadRoute'] = route('menu-items.upload');
            $data['uploadTemplate'] = route('menu-items.template');
            return view('menu-items.upload-items',$data);
	    }
	    
	    public function uploadTemplate(){

			$header = array('MENU CODE');
			$segmentations =  MenuSegmentation::where('status','ACTIVE')->orderBy('menu_segment_column_description','ASC')->get();
			$old_item_codes = MenuOldCodeMaster::where('status','ACTIVE')->orderBy('menu_old_code_column_description','ASC')->get();
			$prices = MenuPriceMaster::where('status','ACTIVE')->orderBy('menu_price_column_description','ASC')->get();
			$group_choices = MenuChoiceGroup::where('status','ACTIVE')->orderBy('menu_choice_group_column_description','ASC')->get();

			
			foreach($old_item_codes as $old_codes){
				array_push($header,$old_codes->menu_old_code_column_description);
			}

			array_push($header,'POS OLD DESCRIPTION');
			array_push($header,'MENU DESCRIPTION');
			array_push($header,'PRODUCT TYPE');

			foreach($group_choices as $choice){
				array_push($header,$choice->menu_choice_group_column_description);
				array_push($header,$choice->menu_choice_group_column_description.' SKU');
			}
			
			array_push($header,'MENU TYPE');
			array_push($header,'MAIN CATEGORY');
			array_push($header,'SUB CATEGORY');

			foreach($prices as $price){
				array_push($header,$price->menu_price_column_description);
			}
			
			array_push($header,'ORIGINAL CONCEPT');
			array_push($header,'STATUS');

			foreach($segmentations as $segment){
				array_push($header,$segment->menu_segment_column_description);
			}
			
			$export = new ExcelTemplate([$header]);
            return Excel::download($export, 'menu-items-'.date("Ymd").'-'.date("h.i.sa").'.csv');
	    }
	    
	    public function uploadItems(Request $request){
	        set_time_limit(0);
				
			$errors = array();
			$path_excel = $request->file('import_file')->store('temp');
			$path = storage_path('app').'/'.$path_excel;
            HeadingRowFormatter::default('none');
            $headings = (new HeadingRowImport)->toArray($path);
            //check headings
            $header = array('MENU CODE');
			$segmentations =  MenuSegmentation::where('status','ACTIVE')->orderBy('menu_segment_column_description','ASC')->get();
			$old_item_codes = MenuOldCodeMaster::where('status','ACTIVE')->orderBy('menu_old_code_column_description','ASC')->get();
			$prices = MenuPriceMaster::where('status','ACTIVE')->orderBy('menu_price_column_description','ASC')->get();
			$group_choices = MenuChoiceGroup::where('status','ACTIVE')->orderBy('menu_choice_group_column_description','ASC')->get();

			
			foreach($old_item_codes as $old_codes){
				array_push($header,$old_codes->menu_old_code_column_description);
			}

			array_push($header,'POS OLD DESCRIPTION');
			array_push($header,'MENU DESCRIPTION');
			array_push($header,'PRODUCT TYPE');

			foreach($group_choices as $choice){
				array_push($header,$choice->menu_choice_group_column_description);
				array_push($header,$choice->menu_choice_group_column_description.' SKU');
			}
			
			array_push($header,'MENU TYPE');
			array_push($header,'MAIN CATEGORY');
			array_push($header,'SUB CATEGORY');

			foreach($prices as $price){
				array_push($header,$price->menu_price_column_description);
			}
			
			array_push($header,'ORIGINAL CONCEPT');
			array_push($header,'STATUS');

			foreach($segmentations as $segment){
				array_push($header,$segment->menu_segment_column_description);
			}

			for ($i=0; $i < sizeof($headings[0][0]); $i++) {
				if (!in_array($headings[0][0][$i], $header)) {
					$unMatch[] = $headings[0][0][$i];
				}
			}

			if(!empty($unMatch)) {
                return redirect()->back()->with(['message_type' => 'danger', 'message' => 'Failed ! Please check template headers, mismatched detected.']);
			}
            HeadingRowFormatter::default('slug');
			Excel::import(new MenuItemsImport, $path);
			return redirect('admin/menu_items')->with(['message_type' => 'success', 'message' => 'Upload complete!']);
	    }
	    
	    public function exportItems(Request $request){
			
		   $filename = $request->input('filename');
		   return Excel::download(new MenuItemsExport, $filename.'.xlsx');
	    }

	}
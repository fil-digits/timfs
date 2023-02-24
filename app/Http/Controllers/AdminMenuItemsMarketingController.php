<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminMenuItemsMarketingController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "ingredient_name_4";
			$this->limit = "20";
			$this->orderby = "id,desc";
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
			$this->button_export = false;
			$this->table = "menu_items";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Approval Status (Marketing)","name"=>"id","join"=>"menu_ingredients_approval,marketing_approval_status","join_id"=>"menu_items_id"];
			$this->col[] = ["label"=>"Tasteless Menu Code","name"=>"tasteless_menu_code"];
			$this->col[] = ["label"=>"Menu Item Description","name"=>"menu_item_description"];
			$this->col[] = ["label"=>"Price - Dine In","name"=>"menu_price_dine"];
			$this->col[] = ["label"=>"Price - Delivery","name"=>"menu_price_dlv"];
			$this->col[] = ["label"=>"Food Cost","name"=>"food_cost"];
			$this->col[] = ["label"=>"Food Cost Percentage","name"=>"food_cost_percentage"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Action Type','name'=>'action_type','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Tasteless Menu Code','name'=>'tasteless_menu_code','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Old Code 4','name'=>'old_code_4','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Old Code 3','name'=>'old_code_3','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Old Code 2','name'=>'old_code_2','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Old Code 1','name'=>'old_code_1','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Menu Item Description','name'=>'menu_item_description','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Pos Old Item Description','name'=>'pos_old_item_description','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Menu Categories Id','name'=>'menu_categories_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'menu_categories,id'];
			$this->form[] = ['label'=>'Menu Types Id','name'=>'menu_types_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'menu_types,id'];
			$this->form[] = ['label'=>'Menu Product Types Id','name'=>'menu_product_types_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'menu_product_types,id'];
			$this->form[] = ['label'=>'Menu Transaction Types Id','name'=>'menu_transaction_types_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'menu_transaction_types,id'];
			$this->form[] = ['label'=>'Menu Subcategories Id','name'=>'menu_subcategories_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'menu_subcategories,id'];
			$this->form[] = ['label'=>'Choices Skugroup 4','name'=>'choices_skugroup_4','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Choices Group 4','name'=>'choices_group_4','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Choices Skugroup 3','name'=>'choices_skugroup_3','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Choices Group 3','name'=>'choices_group_3','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Choices Skugroup 2','name'=>'choices_skugroup_2','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Choices Group 2','name'=>'choices_group_2','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Choices Skugroup 1','name'=>'choices_skugroup_1','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Choices Group 1','name'=>'choices_group_1','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Tax Codes Id','name'=>'tax_codes_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'tax_codes,id'];
			$this->form[] = ['label'=>'Status','name'=>'status','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Food Cost','name'=>'food_cost','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Food Cost Percentage','name'=>'food_cost_percentage','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Menu Cost Price','name'=>'menu_cost_price','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Menu Selling Price','name'=>'menu_selling_price','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Menu Price Dlv','name'=>'menu_price_dlv','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Menu Price Take','name'=>'menu_price_take','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Menu Price Dine','name'=>'menu_price_dine','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation','name'=>'segmentation','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Uom 4','name'=>'uom_4','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Ingredient Qty 4','name'=>'ingredient_qty_4','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Ingredient Name 4','name'=>'ingredient_name_4','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Ingredient Code 4','name'=>'ingredient_code_4','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Uom 3','name'=>'uom_3','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Ingredient Qty 3','name'=>'ingredient_qty_3','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Ingredient Name 3','name'=>'ingredient_name_3','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Ingredient Code 3','name'=>'ingredient_code_3','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Uom 2','name'=>'uom_2','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Ingredient Qty 2','name'=>'ingredient_qty_2','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Ingredient Name 2','name'=>'ingredient_name_2','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Ingredient Code 2','name'=>'ingredient_code_2','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Uom 1','name'=>'uom_1','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Ingredient Qty 1','name'=>'ingredient_qty_1','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Ingredient Name 1','name'=>'ingredient_name_1','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Ingredient Code 1','name'=>'ingredient_code_1','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Any','name'=>'segmentation_any','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Bbd','name'=>'segmentation_bbd','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Eyb','name'=>'segmentation_eyb','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Cbl','name'=>'segmentation_cbl','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Com','name'=>'segmentation_com','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Fmr','name'=>'segmentation_fmr','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Fwb','name'=>'segmentation_fwb','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Fzb','name'=>'segmentation_fzb','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Hmk','name'=>'segmentation_hmk','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Htw','name'=>'segmentation_htw','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Kkd','name'=>'segmentation_kkd','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Lps','name'=>'segmentation_lps','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Lbs','name'=>'segmentation_lbs','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Mtd','name'=>'segmentation_mtd','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Ppd','name'=>'segmentation_ppd','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Pze','name'=>'segmentation_pze','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Psn','name'=>'segmentation_psn','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Ppr','name'=>'segmentation_ppr','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Rcf','name'=>'segmentation_rcf','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Scb','name'=>'segmentation_scb','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Sch','name'=>'segmentation_sch','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Sdh','name'=>'segmentation_sdh','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Smk','name'=>'segmentation_smk','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Twu','name'=>'segmentation_twu','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Tbf','name'=>'segmentation_tbf','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Tgd','name'=>'segmentation_tgd','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Wkd','name'=>'segmentation_wkd','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Wks','name'=>'segmentation_wks','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation Wmn','name'=>'segmentation_wmn','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation 1','name'=>'segmentation_1','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation 2','name'=>'segmentation_2','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation 3','name'=>'segmentation_3','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation 4','name'=>'segmentation_4','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation 5','name'=>'segmentation_5','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation 6','name'=>'segmentation_6','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation 7','name'=>'segmentation_7','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation 8','name'=>'segmentation_8','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation 9','name'=>'segmentation_9','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation 10','name'=>'segmentation_10','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation 11','name'=>'segmentation_11','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation 12','name'=>'segmentation_12','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation 13','name'=>'segmentation_13','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation 14','name'=>'segmentation_14','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Segmentation 15','name'=>'segmentation_15','type'=>'text','validation'=>'required|min:1|max:255'];
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
	        $this->alert        = array();
	                

	        
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
			$status = ['PENDING'];
			$query
				->whereIn('menu_ingredients_approval.marketing_approval_status', $status)
				->orderByRaw("FIELD(menu_ingredients_approval.marketing_approval_status, 'PENDING', 'APPROVED', 'REJECTED') ASC");;
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	if ($column_index == 2) {
				if ($column_value == "REJECTED") {
					$column_value = '<span class="label label-danger">REJECTED</span>';
				} else if ($column_value == "APPROVED") {
					$column_value = '<span class="label label-success">APPROVED</span>';
				} else if ($column_value == 'PENDING') {
					$column_value = '<span class="label label-warning">PENDING</span>';
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



	    //By the way, you can still create your own method in here... :) 

		public function getEdit($id) {
			if (CRUDBooster::myPrivilegeName() != 'Chef' &&
				CRUDBooster::myPrivilegeId() != '1' &&
				CRUDBooster::myPrivilegeName() != 'Ingredient Approver (Accounting)' &&
				CRUDBooster::myPrivilegeName() != 'Ingredient Approver (Marketing)') return redirect('admin/menu_items')->with(['message_type' => 'danger', 'message' => 'You do not have the access to edit the item.']);

			$data = [];
			$data['item'] = DB::table('menu_items')
				->select('*', 'menu_items.id as id')
				->where('menu_items.id', $id)
				->leftJoin('menu_ingredients_approval', 'menu_ingredients_approval.menu_items_id', '=', 'menu_items.id')
				->get()[0];

			$data['privilege'] = CRUDBooster::myPrivilegeName();

			$is_approved = $data['item']->accounting_approval_status == 'APPROVED' && $data['item']->marketing_approval_status == 'APPROVED' || 
			$data['item']->accounting_approval_status == null && $data['item']->marketing_approval_status == null;

			$data['current_ingredients'] = DB::table('menu_ingredients_details_temp')
				->where('menu_items_id', $id)
				->where('menu_ingredients_details_temp.status', 'ACTIVE')
				->leftJoin('item_masters', 'menu_ingredients_details_temp.item_masters_id', '=', 'item_masters.id')
				->select(\DB::raw('item_masters.id as item_masters_id'),
					'is_selected',
					'is_primary',
					'qty',
					'cost',
					'ingredient_group',
					'uom_id',
					'packagings.packaging_description',
					\DB::raw('item_masters.ttp / item_masters.packaging_size as ingredient_cost'),
					'item_masters.full_item_description')
				->leftJoin('packagings', 'menu_ingredients_details_temp.uom_id', '=', 'packagings.id')
				->orderBy('ingredient_group', 'ASC')
				->orderBy('row_id', 'ASC')
				->get();
											
			$data['item_masters'] = DB::table('item_masters')
				->where('sku_statuses_id', '!=', '2')
				->select(\DB::raw('item_masters.id as item_masters_id'),
					'item_masters.packagings_id',
					\DB::raw('item_masters.ttp / item_masters.packaging_size as ingredient_cost'),
					'item_masters.full_item_description',
					'item_masters.tasteless_code',
					'packagings.packaging_description',
					'brands.brand_description')
				->leftJoin('packagings','item_masters.packagings_id', '=', 'packagings.id')
				->leftJoin('brands', 'item_masters.brands_id', '=', 'brands.id')
				->orderby('full_item_description')
				->get();
			
			return $this->view('menu-items/edit-item', $data);
		}

	    public function getDetail($id) {
			if(!CRUDBooster::isView()) CRUDBooster::redirect(CRUDBooster::adminPath(),trans('crudbooster.denied_access'));
			$data = [];
			$data['item'] = DB::table('menu_items')
				->where('id', $id)
				->get()
				->first();

			$data['ingredients'] = DB::table('menu_ingredients_details')
				->where('menu_items_id', $id)
				->where('menu_ingredients_details.status', 'ACTIVE')
				->select('tasteless_code',
					'item_masters_id',
					'ingredient_group',
					'row_id',
					'is_primary',
					'is_selected',
					'total_cost',
					'full_item_description',
					'qty', 'uom_description',
					'cost')
				->join('item_masters', 'menu_ingredients_details.item_masters_id', '=', 'item_masters.id')
				->leftJoin('uoms', 'menu_ingredients_details.uom_id', '=', 'uoms.id')
				->orderby('ingredient_group')
				->get();
			return $this->view('menu-items/detail-item', $data);
		}


	}
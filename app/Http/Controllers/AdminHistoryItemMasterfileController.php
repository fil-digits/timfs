<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use Excel;

	class AdminHistoryItemMasterfileController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}
		
	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "updated_at,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "history_item_masterfile";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
            $this->col = [];
			$this->col[] = ["label"=>"Tasteless Code","name"=>"tasteless_code"];
			$this->col[] = ["label"=>"Action","name"=>"action"];
			$this->col[] = ["label"=>"Item Description","name"=>"item_id","join"=>"item_masters,full_item_description"];
			$this->col[] = ["label"=>"Brand","name"=>"brand_id","join"=>"brands,brand_description"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"item_id","join"=>"item_masters,created_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
            $this->form = [];
			$this->form[] = ['label'=>'Tasteless Code','name'=>'tasteless_code','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Action','name'=>'action','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
            $this->form[] = ['label'=>'Item Description','name'=>'item_id','type'=>'select','width'=>'col-sm-4','datatable'=>'item_masters,full_item_description'];
			$this->form[] = ['label'=>'Brand','name'=>'brand_id','type'=>'select','width'=>'col-sm-4','datatable'=>'brands,brand_description'];
			$this->form[] = ["label"=>"Updated By","name"=>"updated_by",'type'=>'select',"datatable"=>"cms_users,name",'width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Updated Date','name'=>'updated_at', 'type'=>'datetime','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Details','name'=>'details','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];

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
            if (CRUDBooster::getCurrentMethod() == 'getIndex')  //2022-07-04
            {
				$this->index_button[] = ['label' => 'Export TTP History', "url" => CRUDBooster::mainpath("export-ttp-history").'?'.urldecode(http_build_query(@$_GET)), "icon" => "fa fa-download"];
				$this->index_button[] = ['label' => 'Export Purchase Price History', "url" => CRUDBooster::mainpath("export-purchase-price-history").'?'.urldecode(http_build_query(@$_GET)), "icon" => "fa fa-download"];
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
	    
	    //2022-07-04

		public function exportPurchasePrice(Request $request) {
			Excel::create('Purchase-Price-History-' . date("d M Y - h.i.sa"), function ($excel) {
				// Set the title
				$excel->setTitle('PurchasePriceHistory');
				// Chain the setters
				$excel->setCreator('Tasteless IMFS')->setCompany('Tasteless');
				$excel->setDescription('Purchase Price History Export');
	
				$excel->sheet('bartender', function ($sheet) {
					// Set auto size for sheet
					$sheet->setAutoSize(true);
					$sheet->setColumnFormat(array(
						'A' => '@', //for tasteless code
						'D' => '0.00', //for ttp
						'F' => '0.00', //for ttp

					));
	
					$data_ppHistory = DB::table('history_item_masterfile');
					$data_ppHistory->select('history_item_masterfile.tasteless_code', 'item_masters.full_item_description', 'groups.group_description', 
					'history_item_masterfile.purchase_price', 'history_item_masterfile.old_purchase_price','cms_users.name as updatedby','history_item_masterfile.updated_at as updateddate')
					->join('item_masters','history_item_masterfile.item_id','=','item_masters.id')
					->leftJoin('groups','history_item_masterfile.group_id','=','groups.id')
					->leftJoin('cms_users','history_item_masterfile.updated_by','=','cms_users.id')
					->whereNotNull('history_item_masterfile.purchase_price');

					if(\Request::get('filter_column')) {
	
						$filter_column = \Request::get('filter_column');
						$data_ppHistory->where(function($w) use ($filter_column,$fc) {
							foreach($filter_column as $key=>$fc) {
	
								$value = @$fc['value'];
								$type  = @$fc['type'];
	
								if($type == 'empty') {
									$w->whereNull($key)->orWhere($key,'');
									continue;
								}
	
								if($value=='' || $type=='') continue;
	
								if($type == 'between') continue;
	
								switch($type) {
									default:
										if($key && $type && $value) $w->where($key,$type,$value);
									break;
									case 'like':
									case 'not like':
										$value = '%'.$value.'%';
										if($key && $type && $value) $w->where($key,$type,$value);
									break;
									case 'in':
									case 'not in':
										if($value) {
											$value = explode(',',$value);
											if($key && $value) $w->whereIn($key,$value);
										}
									break;
								}
							}
						});
	
						foreach($filter_column as $key=>$fc) {
							$value = @$fc['value'];
							$type  = @$fc['type'];
							$sorting = @$fc['sorting'];
	
							if($sorting!='') {
								if($key) {
									$data_ppHistory->orderby($key,$sorting);
									$filter_is_orderby = true;
								}
							}
	
							if ($type=='between') {
								if($key && $value) $data_ppHistory->whereBetween($key,$value);
							}
	
							else {
								continue;
							}
						}
					}
						
					$datas_ppHistories = $data_ppHistory->get();
					$headings = array('Tasteless Code', 'Item Description', 'Group','Old Purchase Price','Purchase Price','Updated By','Updated Date');
	
					foreach ($datas_ppHistories as $value) {
						$datas[] = array(
							$value->tasteless_code,
							$value->full_item_description,
							$value->group_description,
							$value->old_purchase_price,
							$value->purchase_price,
							$value->updatedby,
							$value->updateddate
						);
					}
	
					$sheet->fromArray($datas, null, 'A1', false, false);
					$sheet->prependRow(1, $headings);
					$sheet->row(1, function ($row) {
						$row->setBackground('#FFFF00');
						$row->setAlignment('center');
					});
					$sheet->cells('A1:I1', function ($cells) {
						// Set font weight to bold
						$cells->setFontWeight('bold');
						// Set all borders (top, right, bottom, left)
						$cells->setBorder('none', 'none', 'solid', 'none');
					});
	
				});
			})->export('csv');
			
		}

		public function exportSalePrice(Request $request) {
			Excel::create('TTP-History-' . date("d M Y - h.i.sa"), function ($excel) {
				// Set the title
				$excel->setTitle('TTPHistory');
				// Chain the setters
				$excel->setCreator('Tasteless IMFS')->setCompany('Tasteless');
				$excel->setDescription('TTPHistory Export');
	
				$excel->sheet('bartender', function ($sheet) {
					// Set auto size for sheet
					$sheet->setAutoSize(true);
					$sheet->setColumnFormat(array(
						'A' => '@', //for tasteless code
						'D' => '0.00', //for ttp
						'F' => '0.00', //for ttp

					));
	
					$data_ttpHistory = DB::table('history_item_masterfile');
					$data_ttpHistory->select('history_item_masterfile.tasteless_code', 'item_masters.full_item_description', 'groups.group_description', 
					'history_item_masterfile.ttp', 'history_item_masterfile.ttp_percentage','history_item_masterfile.old_ttp', 'history_item_masterfile.old_ttp_percentage','cms_users.name as updatedby','history_item_masterfile.updated_at as updateddate')
					->join('item_masters','history_item_masterfile.item_id','=','item_masters.id')
					->leftJoin('groups','history_item_masterfile.group_id','=','groups.id')
					->leftJoin('cms_users','history_item_masterfile.updated_by','=','cms_users.id')
					->whereNotNull('history_item_masterfile.ttp');

					if(\Request::get('filter_column')) {
	
						$filter_column = \Request::get('filter_column');
						$data_ttpHistory->where(function($w) use ($filter_column,$fc) {
							foreach($filter_column as $key=>$fc) {
	
								$value = @$fc['value'];
								$type  = @$fc['type'];
	
								if($type == 'empty') {
									$w->whereNull($key)->orWhere($key,'');
									continue;
								}
	
								if($value=='' || $type=='') continue;
	
								if($type == 'between') continue;
	
								switch($type) {
									default:
										if($key && $type && $value) $w->where($key,$type,$value);
									break;
									case 'like':
									case 'not like':
										$value = '%'.$value.'%';
										if($key && $type && $value) $w->where($key,$type,$value);
									break;
									case 'in':
									case 'not in':
										if($value) {
											$value = explode(',',$value);
											if($key && $value) $w->whereIn($key,$value);
										}
									break;
								}
							}
						});
	
						foreach($filter_column as $key=>$fc) {
							$value = @$fc['value'];
							$type  = @$fc['type'];
							$sorting = @$fc['sorting'];
	
							if($sorting!='') {
								if($key) {
									$data_ttpHistory->orderby($key,$sorting);
									$filter_is_orderby = true;
								}
							}
	
							if ($type=='between') {
								if($key && $value) $data_ttpHistory->whereBetween($key,$value);
							}
	
							else {
								continue;
							}
						}
					}
						
					$datas_ttpHistories = $data_ttpHistory->get();
					$headings = array('Tasteless Code', 'Item Description', 'Group','Old TTP','Old TTP Percentage','TTP','TTP Percentage','Updated By','Updated Date');
	
					foreach ($datas_ttpHistories as $value) {
						$datas[] = array(
							$value->tasteless_code,
							$value->full_item_description,
							$value->group_description,
							$value->old_ttp,
							$value->old_ttp_percentage,
							$value->ttp,
							$value->ttp_percentage,
							$value->updatedby,
							$value->updateddate
						);
					}
	
					$sheet->fromArray($datas, null, 'A1', false, false);
					$sheet->prependRow(1, $headings);
					$sheet->row(1, function ($row) {
						$row->setBackground('#FFFF00');
						$row->setAlignment('center');
					});
					$sheet->cells('A1:I1', function ($cells) {
						// Set font weight to bold
						$cells->setFontWeight('bold');
						// Set all borders (top, right, bottom, left)
						$cells->setBorder('none', 'none', 'solid', 'none');
					});
	
				});
			})->export('csv');
		}

		//end-2022-07-04


	}
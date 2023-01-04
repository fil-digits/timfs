<?php namespace App\Http\Controllers;

	use Session;
	use DB;
	use Maatwebsite\Excel\HeadingRowImport;
	use Maatwebsite\Excel\Imports\HeadingRowFormatter;
	use Maatwebsite\Excel\Facades\Excel;
	use CRUDBooster;
	use App\Brand;
	use App\ItemMaster;
	use App\ItemMasterApproval;
	use App\ApprovalWorkflowSetting;
	use Illuminate\Http\Request;
	use App\CodeCounter;
	use App\Exports\ExcelTemplate;
	use App\Exports\BartenderExport;
	use App\Exports\ItemExport;
	use App\Exports\POSExport;
	use App\Exports\QBExport;
	use App\Group;
	use App\Segmentation;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Redirect;
	use Carbon\Carbon;
	use Illuminate\Support\Facades\Schema;

	class AdminItemMastersController extends \crocodicstudio\crudbooster\controllers\CBController {
	    
        private $pre_ttp_price = 0;
        private $segmentation_editForm = [];
        private $counter = 0;
		protected $diffData;
		protected $segments;

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
			$this->diffData = [];
			$this->segments = Segmentation::where('status','ACTIVE')->orderBy('segment_column_description','ASC')->get();
		}
	    
	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "supplier_item_code";
			$this->limit = "20";
			$this->orderby = "tasteless_code,desc";
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
			$this->button_export = false;
			$this->table = "item_masters";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
            $this->col[] = ["label" => "Item ID", "name" => "id", "visible" => false];
    		$this->col[] = ["label" => "Tasteless Code", "name" => "tasteless_code","visible" =>  false];
    		$this->col[] = ["label" => "Preferred Vendor", "name" => "suppliers_id", "join" => "suppliers,last_name", "visible" => CRUDBooster::myColumnView()->supplier ? true : false];
    		$this->col[] = ["label" => "Item", "name" => "tasteless_code", "visible" =>  true];
    		$this->col[] = ["label" => "Description", "name" => "full_item_description", "visible" => CRUDBooster::myColumnView()->full_item_description ? true : false];
    		$this->col[] = ["label"=>"Brand Description","name"=>"brands_id","join"=>"brands,brand_description","visible" => CRUDBooster::myColumnView()->brand_description ? true : false];
    		$this->col[] = ["label" => "Category Description", "name" => "categories_id", "join" => "categories,category_description", "visible" => CRUDBooster::myColumnView()->category_description ? true : false];
    		$this->col[] = ["label" => "Subcategory Description", "name" => "subcategories_id", "join" => "subcategories,subcategory_description", "visible" => CRUDBooster::myColumnView()->subcategory ? true : false];
            $this->col[] = ["label" => "Fulfillment Type", "name" => "fulfillment_types_id", "join" => "fulfillment_types,fulfillment_type"];
            $this->col[] = ["label" => "Packaging Size", "name" => "packaging_size", "visible" => CRUDBooster::myColumnView()->packaging_size ? true : false];
    		$this->col[] = ["label" => "Packaging UOM", "name" => "packagings_id", "join" => "packagings,packaging_code", "visible" => CRUDBooster::myColumnView()->packaging ? true : false];
    		$this->col[] = ["label"=>"Currency","name"=>"currencies_id","join"=>"currencies,currency_code","visible" => CRUDBooster::myColumnView()->currency ? true : false];
    		$this->col[] = ["label" => "Supplier Cost", "name" => "purchase_price", "visible" => CRUDBooster::myColumnView()->purchase_price ? true : false];
			$this->col[] = ["label" => "Sales Price", "name" => "ttp", "visible" => CRUDBooster::myColumnView()->ttp ? true : false];
			$this->col[] = ["label" => "Sales Price Change", "name" => "ttp_price_change", "visible" => CRUDBooster::myColumnView()->ttp ? true : false]; //2022-07-04
			$this->col[] = ["label" => "Sales Price Effective Date", "name" => "ttp_price_effective_date", "visible" => CRUDBooster::myColumnView()->ttp ? true : false]; //2022-07-04
    		$this->col[] = ["label" => "Landed Cost", "name" => "landed_cost", "visible" => CRUDBooster::myColumnView()->landed_cost ? true : false];
            $this->col[] = ["label" => "Commi Margin", "name" => "ttp_percentage", "visible" => CRUDBooster::myColumnView()->landed_cost ? true : false];
            $this->col[] = ["label" => "Created By", "name" => "created_by", "join" => "cms_users,name", "visible" => CRUDBooster::myColumnView()->create_by ? true : false];
    		$this->col[] = ["label" => "Updated By", "name" => "updated_by", "join" => "cms_users,name", "visible" => CRUDBooster::myColumnView()->update_date ? true : false];
		
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];

			if (in_array(CRUDBooster::getCurrentMethod(), ['getAdd','postAddSave'])){

                $this->form[] = [
                    'label' => 'Description', 'name' => 'full_item_description', 'type' => 'text',
                    'validation' => CRUDBooster::myAddForm()->full_item_description ? 'required|min:5|max:255' : '', 'width' => 'col-sm-4',
                    'style' => CRUDBooster::myAddForm()->full_item_description ?: 'display:none;'
                ];
                
                $this->form[] = ['label'=>'Brand Description','name'=>'brands_id','type'=>'select2',
    				'validation'=>CRUDBooster::myAddForm()->brand_description ? 'required|integer|min:0' : '','width'=>'col-sm-4',
    				'datatable'=>'brands,brand_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->brand_description ? : 'display:none;'];
    
    
                $this->form[] = [
    				'label' => 'Tax Code', 'name' => 'tax_codes_id', 'type' => 'select2',
    				'validation' => CRUDBooster::myAddForm()->tax_code ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
    				'datatable' => 'tax_codes,tax_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->tax_code ?: 'display:none;'
    			];
    
                $this->form[] = [
                    'label' => 'Account', 'name' => 'accounts_id', 'type' => 'select2',
                    'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
                    'datatable' => 'accounts,group_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->group ?: 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'COGS Account', 'name' => 'cogs_accounts_id', 'type' => 'select2',
                    'validation' => CRUDBooster::myAddForm()->group ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                    'datatable' => 'cogs_accounts,group_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->group ?: 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'Asset Account', 'name' => 'asset_accounts_id', 'type' => 'select2',
                    'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
                    'datatable' => 'asset_accounts,group_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->group ?: 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'Purchase Description', 'name' => 'purchase_description', 'type' => 'text',
                    'validation' => 'required|min:5|max:255', 'width' => 'col-sm-4', 'readonly' => true
                ];
              
                $this->form[] = [
    				'label' => 'Fulfillment Type', 'name' => 'fulfillment_types_id', 'type' => 'select2',
    				'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
    				'datatable' => 'fulfillment_types,fulfillment_type', 'datatable_where' => "status='ACTIVE'"
    			];
    
                $this->form[] = [
    				'label' => 'U/M', 'name' => 'uoms_id', 'type' => 'select2',
    				'validation' => CRUDBooster::myAddForm()->uom ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
    				'datatable' => 'uoms,uom_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->uom ?: 'display:none;'
    			];
    
    			$this->form[] = [
    				'label' => 'U/M Set', 'name' => 'uoms_set_id', 'type' => 'select2',
    				'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
    				'datatable' => 'uoms_set,uom_description', 'datatable_where' => "status='ACTIVE'"
    			];
    			
    			$this->form[] = ['label'=>'Currency','name'=>'currencies_id','type'=>'select2',
					'validation'=>CRUDBooster::myAddForm()->currency ? 'required|integer|min:0' : '','width'=>'col-sm-4',
					'datatable'=>'currencies,currency_code','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->currency ? : 'display:none;'];
    
                $this->form[] = [
                    'label' => 'Supplier Cost', 'name' => 'purchase_price', 'type' => 'number',
                    'validation' => CRUDBooster::myAddForm()->purchase_price ? 'required' : '', 'width' => 'col-sm-4',
                    'style' => CRUDBooster::myAddForm()->purchase_price ?: 'display:none;'
                ];
				
				$this->form[] = [
                    'label' => 'Sales Price', 'name' => 'ttp', 'type' => 'number',
                    'validation' => CRUDBooster::myAddForm()->ttp ? 'required' : '', 'width' => 'col-sm-4',
                    'style' => CRUDBooster::myAddForm()->ttp ?: 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'Commi Margin', 'name' => 'ttp_percentage', 'type' => 'number', 'readonly' => true,
                    'validation' => CRUDBooster::myAddForm()->ttp_percentage ? 'required' : '', 'width' => 'col-sm-4',
                    'style' => CRUDBooster::myAddForm()->ttp_percentage ?: 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'Landed Cost', 'name' => 'landed_cost', 'type' => 'number',
                    'validation' => CRUDBooster::myAddForm()->landed_cost ? 'required' : '', 'width' => 'col-sm-4',
                    'style' => CRUDBooster::myAddForm()->landed_cost ?: 'display:none;'
                ];
                
                $this->form[] = [
                    'label' => 'Sales Price', 'name' => 'price', 'type' => 'number',
                    'validation' => 'required', 'width' => 'col-sm-4', 'readonly' => true, 'style' => 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'Preferred Vendor', 'name' => 'suppliers_id', 'type' => 'select2',
                    'disabled' => CRUDBooster::myEditReadOnly()->supplier ? true : false,
                    'validation' => CRUDBooster::myEditForm()->supplier ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                    'datatable' => 'suppliers,last_name', 'datatable_where' => "approval_status != 'NULL'", 'style' => CRUDBooster::myAddForm()->supplier ?: 'display:none;'
                ];
				
                $this->form[] = [
                    'label' => 'Reorder Pt (Min)', 'name' => 'reorder_pt', 'type' => 'number',
                    'validation' => 'required|min:0.00', 'width' => 'col-sm-4'
                ];
				
                $this->form[] = [
                    'label' => 'Group', 'name' => 'groups_id', 'type' => 'select2',
                    'validation' => CRUDBooster::myAddForm()->group ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                    'datatable' => 'groups,group_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->group ?: 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'Category Description', 'name' => 'categories_id', 'type' => 'select',
                    'validation' => CRUDBooster::myAddForm()->category_description ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                    'datatable' => 'categories,category_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->category_description ?: 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'Subcategory Description', 'name' => 'subcategories_id', 'type' => 'select',
                    'validation' => CRUDBooster::myAddForm()->subcategory ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                    'datatable' => 'subcategories,subcategory_description', 'datatable_where' => "status=%27ACTIVE%27", 'parent_select' => 'categories_id', 'style' => CRUDBooster::myAddForm()->subcategory ?: 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'Dimension', 'name' => 'packaging_dimension', 'type' => 'text',
                    'validation' => CRUDBooster::myAddForm()->packaging_dimension ? 'max:50' : '', 'width' => 'col-sm-4',
                    'style' => CRUDBooster::myAddForm()->packaging_dimension ?: 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'Packaging Size', 'name' => 'packaging_size', 'type' => 'number',
                    'validation' => CRUDBooster::myAddForm()->packaging_size ? 'required' : '', 'width' => 'col-sm-4',
                    'style' => CRUDBooster::myAddForm()->packaging_size ?: 'display:none;'
                ];
				
                $this->form[] = [
                    'label' => 'Tax Status', 'name' => 'tax_status', 'type' => 'text',
                    'validation' => 'required', 'width' => 'col-sm-4', 'readonly' => true
                ];
    
                $this->form[] = [
                    'label' => 'Supplier Item Code', 'name' => 'supplier_item_code', 'type' => 'text',
                    'validation' => CRUDBooster::myAddForm()->supplier_item_code ? 'max:50' : '', 'width' => 'col-sm-4',
                    'style' => CRUDBooster::myAddForm()->supplier_item_code ?: 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'MOQ Store', 'name' => 'moq_store', 'type' => 'number',
                    'validation' => CRUDBooster::myAddForm()->moq_store ? 'required' : '', 'width' => 'col-sm-4',
                    'style' => CRUDBooster::myAddForm()->moq_store ?: 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'Segmentation', 'name' => 'segmentation', 'type' => 'checkbox-custom',
                    'validation' => CRUDBooster::myAddForm()->segmentation ? 'required' : '', 'width' => 'col-sm-6',
                    'datatable' => 'segmentations,segment_column_description,segment_column_name', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->segmentation ?: 'display:none;'
                ];
                //-----------------------------------

			}
			elseif (in_array(CRUDBooster::getCurrentMethod(), ['getEdit','postEditSave'])){
				
				
			    $this->form[] = ['label'=>'Item','name'=>'tasteless_code','type'=>'text','readonly'=>true,'width'=>'col-sm-4'];

			    //----added by cris 20200630
                $this->form[] = ['label' => 'Active Status', 'name' => 'sku_statuses_id', 'type' => 'select2', CRUDBooster::myEditForm()->sku_status ? : 'readonly' => true,
                    'validation' => CRUDBooster::myAddForm()->sku_status ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                    'datatable' => 'sku_statuses,sku_status_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->sku_status ?: 'display:none;'
                ];
                    
                $this->form[] = [
                    'label' => 'Type', 'name' => 'type', 'type' => 'select',
                    'validation' => 'required', 'width' => 'col-sm-4', 'dataenum'=>'Inventory Part'
                ];
				
                $this->form[] = [
                    'label' => 'Description', 'name' => 'full_item_description', 'type' => 'text', CRUDBooster::myEditForm()->full_item_description ? : 'readonly' => true,
                    'validation' => CRUDBooster::myAddForm()->full_item_description ? 'required|min:5|max:255' : '', 'width' => 'col-sm-4',
                    'style' => CRUDBooster::myAddForm()->full_item_description ?: 'display:none;'
                ];
                    
                $this->form[] = ['label'=>'Brand Description','name'=>'brands_id','type'=>'select2', CRUDBooster::myEditForm()->brands_id ? : 'readonly' => true,
                    'validation'=>CRUDBooster::myAddForm()->brand_description ? 'required|integer|min:0' : '','width'=>'col-sm-4',
                    'datatable'=>'brands,brand_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->brand_description ? : 'display:none;'];
    
    
                $this->form[] = [
                    'label' => 'Tax Code', 'name' => 'tax_codes_id', 'type' => 'select2', CRUDBooster::myEditForm()->tax_codes_id ? : 'readonly' => true,
                    'validation' => CRUDBooster::myAddForm()->tax_code ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                    'datatable' => 'tax_codes,tax_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->tax_code ?: 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'Account', 'name' => 'accounts_id', 'type' => 'select2', CRUDBooster::myEditForm()->accounts_id ? : 'readonly' => true,
                    'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
                    'datatable' => 'accounts,group_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->group ?: 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'COGS Account', 'name' => 'cogs_accounts_id', 'type' => 'select2', CRUDBooster::myEditForm()->cogs_accounts_id ? : 'readonly' => true,
                    'validation' => CRUDBooster::myAddForm()->group ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                    'datatable' => 'cogs_accounts,group_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->group ?: 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'Asset Account', 'name' => 'asset_accounts_id', 'type' => 'select2', CRUDBooster::myEditForm()->asset_accounts_id ? : 'readonly' => true,
                    'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
                    'datatable' => 'asset_accounts,group_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->group ?: 'display:none;'
                ];
				
                $this->form[] = [
                    'label' => 'Purchase Description', 'name' => 'purchase_description', 'type' => 'text', CRUDBooster::myEditForm()->purchase_description ? : 'readonly' => true,
                    'validation' => 'required|min:5|max:255', 'width' => 'col-sm-4', 'readonly' => true
                ];
				
                $this->form[] = [
    				'label' => 'Fulfillment Type', 'name' => 'fulfillment_types_id', 'type' => 'select2',
    				'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
    				'datatable' => 'fulfillment_types,fulfillment_type', 'datatable_where' => "status='ACTIVE'"
    			];

                $this->form[] = [
                    'label' => 'U/M', 'name' => 'uoms_id', 'type' => 'select2', CRUDBooster::myEditForm()->uoms_id ? : 'readonly' => true,
                    'validation' => CRUDBooster::myAddForm()->uom ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                    'datatable' => 'uoms,uom_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->uom ?: 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'U/M Set', 'name' => 'uoms_set_id', 'type' => 'select2', CRUDBooster::myEditForm()->uoms_set_id ? : 'readonly' => true,
                    'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
                    'datatable' => 'uoms_set,uom_description', 'datatable_where' => "status='ACTIVE'"
                ];

                $this->form[] = ['label'=>'Currency','name'=>'currencies_id','type'=>'select2', CRUDBooster::myEditForm()->currency ? : 'readonly' => true,
                    // 'disabled'=>CRUDBooster::myEditReadOnly()->currency ? true : false,
                    'validation'=>CRUDBooster::myEditForm()->currency ? 'required|integer|min:0' : '','width'=>'col-sm-4',
                    'datatable'=>'currencies,currency_code','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myEditForm()->currency ? : 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'Supplier Cost', 'name' => 'purchase_price', 'type' => 'number',
                    'validation' => CRUDBooster::myAddForm()->purchase_price ? 'required' : '', 'width' => 'col-sm-4',
                    'style' => CRUDBooster::myAddForm()->purchase_price ?: 'display:none;'
                ];
				
                $this->form[] = [
                    'label' => 'Sales Price', 'name' => 'ttp', 'type' => 'number',
                    'validation' => CRUDBooster::myAddForm()->ttp ? 'required' : '', 'width' => 'col-sm-4',
                    'style' => CRUDBooster::myAddForm()->ttp ?: 'display:none;'
                ];

				$this->form[] = [
                    'label' => 'Sales Price Change', 'name' => 'ttp_price_change', 'type' => 'number',
                    'width' => 'col-sm-4',
                    'style' => CRUDBooster::myAddForm()->ttp ?: 'display:none;'
                ];

				$this->form[] = [
                    'label' => 'Sales Price Effective Date', 'name' => 'ttp_price_effective_date', 'type' => 'date',
                    'width' => 'col-sm-4',
                    'style' => CRUDBooster::myAddForm()->ttp ?: 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'Commi Margin', 'name' => 'ttp_percentage', 'type' => 'number', 'readonly' => true,
                    'validation' => CRUDBooster::myAddForm()->ttp_percentage ? 'required' : '', 'width' => 'col-sm-4',
                    'style' => CRUDBooster::myAddForm()->ttp_percentage ?: 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'Landed Cost', 'name' => 'landed_cost', 'type' => 'number',
                    'validation' => CRUDBooster::myAddForm()->landed_cost ? 'required' : '', 'width' => 'col-sm-4',
                    'style' => CRUDBooster::myAddForm()->landed_cost ?: 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'Sales Price', 'name' => 'price', 'type' => 'number',
                    'validation' => 'required', 'width' => 'col-sm-4', 'readonly' => true, 'style' => 'display:none;'
                ];
    
                $this->form[] = [
                    'label' => 'Preferred Vendor', 'name' => 'suppliers_id', 'type' => 'select2', CRUDBooster::myEditForm()->suppliers_id ? : 'readonly' => true,
                    // 'disabled' => CRUDBooster::myEditReadOnly()->supplier ? true : false,
                    'validation' => CRUDBooster::myEditForm()->supplier ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                    'datatable' => 'suppliers,last_name', 'datatable_where' => "approval_status != 'NULL'", 'style' => CRUDBooster::myAddForm()->supplier ?: 'display:none;'
                ];
        
                    // $this->form[] = ['label' => 'Tax Agency', 'name' => 'tax_agency', 'type' => 'text', 'width' => 'col-sm-4'];
        
                    $this->form[] = [
                        'label' => 'Reorder Pt (Min)', 'name' => 'reorder_pt', 'type' => 'number',
                        'validation' => 'required|min:0.00', 'width' => 'col-sm-4'
                    ];
        
                    // $this->form[] = ['label' => 'MPN', 'name' => 'mpn', 'type' => 'text', 'width' => 'col-sm-4'];
        
                    $this->form[] = [
                        'label' => 'Group', 'name' => 'groups_id', 'type' => 'select2', CRUDBooster::myEditForm()->groups_id ? : 'readonly' => true,
                        'validation' => CRUDBooster::myAddForm()->group ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                        'datatable' => 'groups,group_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->group ?: 'display:none;'
                    ];
        
                    $this->form[] = [
                        'label' => 'Category Description', 'name' => 'categories_id', 'type' => 'select',
                        'validation' => CRUDBooster::myAddForm()->category_description ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                        'datatable' => 'categories,category_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->category_description ?: 'display:none;'
                    ];
        
                    $this->form[] = [
                        'label' => 'Subcategory Description', 'name' => 'subcategories_id', 'type' => 'select', 
                        'validation' => CRUDBooster::myAddForm()->subcategory ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                        'datatable' => 'subcategories,subcategory_description', 'datatable_where' => "status=%27ACTIVE%27", 'parent_select' => 'categories_id', 'style' => CRUDBooster::myAddForm()->subcategory ?: 'display:none;'
                    ];
        
                    $this->form[] = [
                        'label' => 'Dimension', 'name' => 'packaging_dimension', 'type' => 'text', CRUDBooster::myAddForm()->packaging_dimension ? : 'readonly' => true,
                        'validation' => CRUDBooster::myAddForm()->packaging_dimension ? 'max:50' : '', 'width' => 'col-sm-4',
                        'style' => CRUDBooster::myAddForm()->packaging_dimension ?: 'display:none;'
                    ];
                    
                    $this->form[] = [
                        'label' => 'Packaging Size', 'name' => 'packaging_size', 'type' => 'number',
                        'validation' => CRUDBooster::myAddForm()->packaging_size ? 'required' : '', 'width' => 'col-sm-4',
                        'style' => CRUDBooster::myAddForm()->packaging_size ?: 'display:none;'
                    ];
					
                    $this->form[] = [
                        'label' => 'Tax Status', 'name' => 'tax_status', 'type' => 'text', CRUDBooster::myEditForm()->tax_status ? : 'readonly' => true,
                        'validation' => 'required', 'width' => 'col-sm-4', 'readonly' => true
                    ];
        
                    $this->form[] = [
                        'label' => 'Supplier Item Code', 'name' => 'supplier_item_code', 'type' => 'text', CRUDBooster::myEditForm()->supplier_item_code ? : 'readonly' => true,
                        'validation' => CRUDBooster::myAddForm()->supplier_item_code ? 'max:50' : '', 'width' => 'col-sm-4',
                        'style' => CRUDBooster::myAddForm()->supplier_item_code ?: 'display:none;'
                    ];
        
                    $this->form[] = [
                        'label' => 'MOQ Store', 'name' => 'moq_store', 'type' => 'number', CRUDBooster::myEditForm()->moq_store ? : 'readonly' => true,
                        'validation' => CRUDBooster::myAddForm()->moq_store ? 'required' : '', 'width' => 'col-sm-4',
                        'style' => CRUDBooster::myAddForm()->moq_store ?: 'display:none;'
                    ];
        
                    $this->form[] = [
                        'label' => 'Segmentation', 'name' => 'segmentation', 'type' => 'checkbox-custom', CRUDBooster::myEditForm()->segmentation ? : 'readonly' => true,
                        // 'disabled' => CRUDBooster::myEditReadOnly()->segmentation ? true : false,
                        'validation' => CRUDBooster::myEditForm()->segmentation ? 'required' : '', 'width' => 'col-sm-6',
                        'datatable' => 'segmentations,segment_column_description,segment_column_name', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myEditForm()->segmentation ?: 'display:none;'
                    ];

			}
			else{

                //----added by cris 20200630----------------------
                    $this->form[] = ['label' => 'Item', 'name' => 'tasteless_code', 'type' => 'text', 'readonly' => true, 'width' => 'col-sm-4'];
                   
                  $this->form[] = [
        				'label' => 'Active Status', 'name' => 'sku_statuses_id', 'type' => 'select2',
        				'validation' => CRUDBooster::myAddForm()->sku_status ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
        				'datatable' => 'sku_statuses,sku_status_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->sku_status ?: 'display:none;'
        			];
                   
                    $this->form[] = [
        				'label' => 'Type', 'name' => 'type', 'type' => 'select',
        				'validation' => 'required', 'width' => 'col-sm-4', 'dataenum'=>'Inventory Part'
        			];
					
                    $this->form[] = [
                        'label' => 'Description', 'name' => 'full_item_description', 'type' => 'text',
                        'validation' => CRUDBooster::myAddForm()->full_item_description ? 'required|min:5|max:255' : '', 'width' => 'col-sm-4',
                        'style' => CRUDBooster::myAddForm()->full_item_description ?: 'display:none;'
                    ];
                    
                    $this->form[] = ['label'=>'Brand Description','name'=>'brands_id','type'=>'select2',
        				'validation'=>CRUDBooster::myAddForm()->brand_description ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        				'datatable'=>'brands,brand_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->brand_description ? : 'display:none;'];
        
        
                    $this->form[] = [
        				'label' => 'Tax Code', 'name' => 'tax_codes_id', 'type' => 'select2',
        				'validation' => CRUDBooster::myAddForm()->tax_code ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
        				'datatable' => 'tax_codes,tax_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->tax_code ?: 'display:none;'
        			];
        
                    $this->form[] = [
                        'label' => 'Account', 'name' => 'accounts_id', 'type' => 'select2',
                        'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
                        'datatable' => 'accounts,group_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->group ?: 'display:none;'
                    ];
        
                    $this->form[] = [
                        'label' => 'COGS Account', 'name' => 'cogs_accounts_id', 'type' => 'select2',
                        'validation' => CRUDBooster::myAddForm()->group ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                        'datatable' => 'cogs_accounts,group_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->group ?: 'display:none;'
                    ];
        
                    $this->form[] = [
                        'label' => 'Asset Account', 'name' => 'asset_accounts_id', 'type' => 'select2',
                        'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
                        'datatable' => 'asset_accounts,group_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->group ?: 'display:none;'
                    ];
					
                    $this->form[] = [
                        'label' => 'Purchase Description', 'name' => 'purchase_description', 'type' => 'text',
                        'validation' => 'required|min:5|max:255', 'width' => 'col-sm-4', 'readonly' => true
                    ];
					
                    $this->form[] = [
        				'label' => 'Fulfillment Type', 'name' => 'fulfillment_types_id', 'type' => 'select2',
        				'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
        				'datatable' => 'fulfillment_types,fulfillment_type', 'datatable_where' => "status='ACTIVE'"
        			];
        
                    $this->form[] = [
        				'label' => 'U/M', 'name' => 'uoms_id', 'type' => 'select2',
        				'validation' => CRUDBooster::myAddForm()->uom ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
        				'datatable' => 'uoms,uom_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->uom ?: 'display:none;'
        			];
        
        			$this->form[] = [
        				'label' => 'U/M Set', 'name' => 'uoms_set_id', 'type' => 'select2',
        				'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
        				'datatable' => 'uoms_set,uom_description', 'datatable_where' => "status='ACTIVE'"
        			];
        
                     $this->form[] = ['label'=>'Currency','name'=>'currencies_id','type'=>'select2','width'=>'col-sm-4',
					'datatable'=>'currencies,currency_code','datatable_where'=>"status='ACTIVE'"];
        
                    $this->form[] = [
                        'label' => 'Supplier Cost', 'name' => 'purchase_price', 'type' => 'number',
                        'validation' => CRUDBooster::myAddForm()->purchase_price ? 'required' : '', 'width' => 'col-sm-4',
                        'style' => CRUDBooster::myAddForm()->purchase_price ?: 'display:none;'
                    ];
					
					$this->form[] = [
                        'label' => 'Sales Price', 'name' => 'ttp', 'type' => 'number',
                        'validation' => CRUDBooster::myAddForm()->ttp ? 'required' : '', 'width' => 'col-sm-4',
                        'style' => CRUDBooster::myAddForm()->ttp ?: 'display:none;'
                    ];

					$this->form[] = [
						'label' => 'Sales Price Change', 'name' => 'ttp_price_change', 'type' => 'number',
						'width' => 'col-sm-4',
						'style' => CRUDBooster::myAddForm()->ttp ?: 'display:none;'
					];
	
					$this->form[] = [
						'label' => 'Sales Price Effective Date', 'name' => 'ttp_price_effective_date', 'type' => 'date',
						'width' => 'col-sm-4',
						'style' => CRUDBooster::myAddForm()->ttp ?: 'display:none;'
					];
        
                    $this->form[] = [
                        'label' => 'Commi Margin', 'name' => 'ttp_percentage', 'type' => 'number', 'readonly' => true,
                        'validation' => CRUDBooster::myAddForm()->ttp_percentage ? 'required' : '', 'width' => 'col-sm-4',
                        'style' => CRUDBooster::myAddForm()->ttp_percentage ?: 'display:none;'
                    ];
        
                    $this->form[] = [
                        'label' => 'Landed Cost', 'name' => 'landed_cost', 'type' => 'number',
                        'validation' => CRUDBooster::myAddForm()->landed_cost ? 'required' : '', 'width' => 'col-sm-4',
                        'style' => CRUDBooster::myAddForm()->landed_cost ?: 'display:none;'
                    ];
        
                    $this->form[] = [
                        'label' => 'Sales Price', 'name' => 'price', 'type' => 'number',
                        'validation' => 'required', 'width' => 'col-sm-4', 'readonly' => true, 'style' => 'display:none;'
                    ];
        
                    $this->form[] = [
                        'label' => 'Preferred Vendor', 'name' => 'suppliers_id', 'type' => 'select2',
                        'disabled' => CRUDBooster::myEditReadOnly()->supplier ? true : false,
                        'validation' => CRUDBooster::myEditForm()->supplier ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                        'datatable' => 'suppliers,last_name', 'datatable_where' => "approval_status != 'NULL'", 'style' => CRUDBooster::myAddForm()->supplier ?: 'display:none;'
                    ];
					
                    $this->form[] = [
                        'label' => 'Reorder Pt (Min)', 'name' => 'reorder_pt', 'type' => 'number',
                        'validation' => 'required|min:0.00', 'width' => 'col-sm-4'
                    ];
					
                    $this->form[] = [
                        'label' => 'Group', 'name' => 'groups_id', 'type' => 'select2',
                        'validation' => CRUDBooster::myAddForm()->group ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                        'datatable' => 'groups,group_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->group ?: 'display:none;'
                    ];
        
                    $this->form[] = [
                        'label' => 'Category Description', 'name' => 'categories_id', 'type' => 'select',
                        'validation' => CRUDBooster::myAddForm()->category_description ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                        'datatable' => 'categories,category_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->category_description ?: 'display:none;'
                    ];
        
                    $this->form[] = [
                        'label' => 'Subcategory Description', 'name' => 'subcategories_id', 'type' => 'select',
                        'validation' => CRUDBooster::myAddForm()->subcategory ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                        'datatable' => 'subcategories,subcategory_description', 'datatable_where' => "status=%27ACTIVE%27", 'parent_select' => 'categories_id', 'style' => CRUDBooster::myAddForm()->subcategory ?: 'display:none;'
                    ];
        
                    $this->form[] = [
                        'label' => 'Dimension', 'name' => 'packaging_dimension', 'type' => 'text',
                        'validation' => CRUDBooster::myAddForm()->packaging_dimension ? 'max:50' : '', 'width' => 'col-sm-4',
                        'style' => CRUDBooster::myAddForm()->packaging_dimension ?: 'display:none;'
                    ];
        
                    $this->form[] = [
                        'label' => 'Packaging Size', 'name' => 'packaging_size', 'type' => 'number',
                        'validation' => CRUDBooster::myAddForm()->packaging_size ? 'required' : '', 'width' => 'col-sm-4',
                        'style' => CRUDBooster::myAddForm()->packaging_size ?: 'display:none;'
                    ];
					
                    //----edited by cris 20201009-----
                    $this->form[] = [
        				'label' => 'Packaging UOM', 'name' => 'packagings_id', 'type' => 'text',
        				'validation' => 'required', 'width' => 'col-sm-4','readonly' => true
        			];
        			//--------------------------------
        
                    $this->form[] = [
        				'label' => 'Tax Status', 'name' => 'tax_codes_id', 'type' => 'select2',
        				'validation' => CRUDBooster::myAddForm()->tax_code ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
        				'datatable' => 'tax_codes,tax_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->tax_code ?: 'display:none;'
        			];
        
                    
        
                    $this->form[] = [
                        'label' => 'Supplier Item Code', 'name' => 'supplier_item_code', 'type' => 'text',
                        'validation' => CRUDBooster::myAddForm()->supplier_item_code ? 'max:50' : '', 'width' => 'col-sm-4',
                        'style' => CRUDBooster::myAddForm()->supplier_item_code ?: 'display:none;'
                    ];
        
                    $this->form[] = [
                        'label' => 'MOQ Store', 'name' => 'moq_store', 'type' => 'number',
                        'validation' => CRUDBooster::myAddForm()->moq_store ? 'required' : '', 'width' => 'col-sm-4',
                        'style' => CRUDBooster::myAddForm()->moq_store ?: 'display:none;'
                    ];
        
                    
        
                    $this->form[] = ['label' => 'Account Number', 'name' => 'chart_accounts_id', 'type' => 'select2', 'datatable' => 'chart_accounts,account_number', 'width' => 'col-sm-4'];
        
                    foreach ($this->segments as $segment) {
        
                        $this->form[] = ['label' => '+' . " " . $segment->segment_column_description, 'name' => $segment->segment_column_name, 'type' => 'checkbox-custom', 'width' => 'col-sm-4'];
                    }
					
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
			if (CRUDBooster::getCurrentMethod() == 'getIndex') 
            {
				$this->index_button[] = ['label' => 'Export Items', "url" => "javascript:showItemExport()", "icon" => "fa fa-download"];
				
				if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(), ["Administrator","Manager (Purchaser)","Encoder (Purchaser)"])){
					$this->index_button[] = ['label' => 'Upload Module', "url" => CRUDBooster::mainpath("upload-module").'?'.urldecode(http_build_query(@$_GET)), "icon" => "fa fa-upload"];
				}
				if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(), ["Administrator","Manager (Purchaser)","Manager (Accounting)","Encoder (Purchaser)","Encoder (Accounting)","Supervisor (Purchaser)"])){
    				$this->index_button[] = ['label' => 'Bartender Format', 'url'=>"javascript:showBartenderExport()",'icon'=>'fa fa-download'];
    				$this->index_button[] = ['label' => 'POS Format', "url" => "javascript:showPOSExport()", "icon" => "fa fa-download"];
    				$this->index_button[] = ['label' => 'QB Item Format', "url" => "javascript:showQBExport()", "icon" => "fa fa-download"];
				    
				}
                if (!CRUDBooster::isSuperadmin() && in_array(CRUDBooster::myPrivilegeName(), ["View I (TTP)", "View II (Purchase Price)", "View III (TTP and Purchase Price)"])) {
                    $this->index_button[] = ['label' => 'QB Item Format', "url" => "javascript:showQBExport()", "icon" => "fa fa-download"];
                    
                }
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
				function showBartenderExport() {
					$('#modal-bartender-export').modal('show');
				}

				function showPOSExport() {
					$('#modal-pos-export').modal('show');
				}

				function showQBExport() {
					$('#modal-qb-export').modal('show');
				}

				function showItemExport() {
					$('#modal-items-export').modal('show');
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
			<div class='modal fade' tabindex='-1' role='dialog' id='modal-bartender-export'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button class='close' aria-label='Close' type='button' data-dismiss='modal'>
								<span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'><i class='fa fa-download'></i> Export Bartender</h4>
						</div>

						<form method='post' target='_blank' action=".CRUDBooster::mainpath("bartender").">
                        <input type='hidden' name='_token' value=".csrf_token().">
                        ".CRUDBooster::getUrlParameters()."
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label>File Name</label>
                                <input type='text' name='filename' class='form-control' required value='Export Bartender - ".date('Y-m-d H:i:s')."'/>
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

			<div class='modal fade' tabindex='-1' role='dialog' id='modal-pos-export'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button class='close' aria-label='Close' type='button' data-dismiss='modal'>
								<span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'><i class='fa fa-download'></i> Export POS Format</h4>
						</div>

						<form method='post' target='_blank' action=".CRUDBooster::mainpath("posformat").">
                        <input type='hidden' name='_token' value=".csrf_token().">
                        ".CRUDBooster::getUrlParameters()."
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label>File Name</label>
                                <input type='text' name='filename' class='form-control' required value='Export POS Format - ".date('Y-m-d H:i:s')."'/>
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

			<div class='modal fade' tabindex='-1' role='dialog' id='modal-qb-export'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button class='close' aria-label='Close' type='button' data-dismiss='modal'>
								<span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'><i class='fa fa-download'></i> Export QB Format</h4>
						</div>

						<form method='post' target='_blank' action=".CRUDBooster::mainpath("qbformat").">
                        <input type='hidden' name='_token' value=".csrf_token().">
                        ".CRUDBooster::getUrlParameters()."
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label>File Name</label>
                                <input type='text' name='filename' class='form-control' required value='Export QB Format - ".date('Y-m-d H:i:s')."'/>
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

			<div class='modal fade' tabindex='-1' role='dialog' id='modal-items-export'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button class='close' aria-label='Close' type='button' data-dismiss='modal'>
								<span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'><i class='fa fa-download'></i> Export Items</h4>
						</div>

						<form method='post' target='_blank' action=".CRUDBooster::mainpath("item-export").">
                        <input type='hidden' name='_token' value=".csrf_token().">
                        ".CRUDBooster::getUrlParameters()."
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label>File Name</label>
                                <input type='text' name='filename' class='form-control' required value='Export Items - ".date('Y-m-d H:i:s')."'/>
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
	        $this->load_js[] = asset("js/item_master.js");
	        
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
	    public function hook_query_index(&$query) 
        {
	        //Your code here
			$query->where(function($sub_query){
			    $create_item_status = ApprovalWorkflowSetting::where('workflow_number', 2)->where('action_type', 'Create')->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('next_state');
			    $update_item_status = ApprovalWorkflowSetting::where('workflow_number', 2)->where('action_type', 'Update')->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('next_state');
			    $update_item_status_1 = ApprovalWorkflowSetting::where('workflow_number', 2)->where('action_type', 'Update')->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('next_state');
			
				$sub_query->where('item_masters.approval_status',	$create_item_status);
				$sub_query->orWhere('item_masters.approval_status',	$update_item_status);
				$sub_query->orWhere('item_masters.approval_status',	$update_item_status_1);
		
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
            // if($column_index == 12){
			// 	$column_value = floatval(number_format($column_value, 5, '.', ''));
			// }
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
			if(CRUDBooster::isSuperadmin())
            {
			    $postdata["myob_item_description"] = $postdata['full_item_description'];
                $postdata["packagings_id"] = $postdata['uoms_set_id'];
                $postdata["sku_statuses_id"] = 1;
                $postdata["type"] = "Inventory Part";
                $postdata["tax_status"] = $postdata['tax_codes_id'];
    			$postdata["sku_statuses_id"] = 1;

			    foreach($this->segments as $segment){
				    $segment_search = $postdata[$segment->segment_column_name];
				    $postdata[$segment->segment_column_name] = $segment_search;
			    }
			    
			    $tasteless_code = 0;
			    $code_column ="";
			    
			    $group = Group::findOrFail($postdata["groups_id"]);
			    
				if (substr($group->group_description, 0, 4) == 'FOOD' || substr($group->group_description, 0, 4) == 'food') {
						$tasteless_code = CodeCounter::where('id', 1)->where('type', 'ITEM MASTER')->value('code_1');
						
						$code_column = "code_1";
				}elseif ($group->group_description == 'BEVERAGE' || $group->group_description == 'beverage') {
						$tasteless_code = CodeCounter::where('id', 1)->where('type', 'ITEM MASTER')->value('code_2');
						
						$code_column = "code_2";
				} elseif ($group->group_description == 'FINISHED GOODS' || $group->group_description == 'finished goods') {
						$tasteless_code = CodeCounter::where('id', 1)->where('type', 'ITEM MASTER')->value('code_1');
						
						$code_column = "code_1";
				} elseif (substr($group->group_description, -8) == 'SUPPLIES' || substr($group->group_description, -8) == 'supplies') {
						$tasteless_code = CodeCounter::where('id', 1)->where('type', 'ITEM MASTER')->value('code_3');
						
						$code_column = "code_3";
				} elseif ($group->group_description == 'CAPEX' || $group->group_description == 'capex') {
						$tasteless_code = CodeCounter::where('id', 1)->where('type', 'ITEM MASTER')->value('code_5');
						
						$code_column = "code_5";
				} elseif ($group->group_description == 'COMPLIMENTARY' || $group->group_description == 'complimentary') {
						$tasteless_code = CodeCounter::where('id', 1)->where('type', 'ITEM MASTER')->value('code_7');
						
						$code_column = "code_7";
				} elseif (substr($group->group_description, -4) == 'FEES' || substr($group->group_description, -4) == 'fees') {
						$tasteless_code = CodeCounter::where('id', 1)->where('type', 'ITEM MASTER')->value('code_4');
						
						$code_column = "code_4";
				} else {
						$tasteless_code = CodeCounter::where('id', 1)->where('type', 'ITEM MASTER')->value('code_6');
						
						$code_column = "code_6";
				}
						
                $postdata["tasteless_code"]	    	    =   $tasteless_code;
			    $postdata["encoder_privilege_id"]		=	CRUDBooster::myPrivilegeId();
			    $postdata["created_by"]					=	CRUDBooster::myId();
			    $postdata["action_type"]				=	"Create";
			    $postdata['approval_status']			= 	ApprovalWorkflowSetting::where('workflow_number', 2)->where('action_type', 'Create')->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('next_state');			    
			
			    CodeCounter::where('type', 'ITEM MASTER')->where('id', 1)->increment($code_column);
			    
			}else{
			    foreach($this->segments as $segment){
				    $segment_search = $postdata[$segment->segment_column_name];
				    $postdata[$segment->segment_column_name] = $segment_search;
			    }

			    $postdata["encoder_privilege_id"]		=	CRUDBooster::myPrivilegeId();
			    $postdata["created_by"]					=	CRUDBooster::myId();
			    $postdata["action_type"]				=	"Create";
			    $postdata['approval_status']			= 	ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('current_state');
	    
			}
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id)
        {        
			//Your code here
			$item_details = ItemMaster::where('id',$id)->get()->toArray();

			//Insert data to temporary table
			ItemMasterApproval::insert($item_details);
            $new_items = ItemMaster::where('id',$id)->first();

            if(CRUDBooster::isSuperadmin())
            {
				//create item to trs

                        DB::connection('mysql_trs')->statement('insert into items (
							tasteless_code, 
							supplier_item_code,  
							full_item_description,  
							brand_id, 
							group_id, 
							fulfillment_types_id, 
							category_id, 
							subcategory_id, 
							uom_id, 
							packaging_id, 
							skustatus_id, 
							currency_id, 
							cost_price, 
							ttp, 
							landed_cost,
							moq_store,
							myob_item_description,
							created_by, 
							updated_by) values (?,?,?,?,?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$new_items['tasteless_code'], $new_items['supplier_itemcode'], $new_items['full_item_description'],  $new_items['brands_id'],$new_items['groups_id'],$new_items['fulfillment_types_id'],$new_items['categories_id'], $new_items['subcategories_id'], $new_items['uoms_id'], $new_items['uoms_set_id'], $new_items['sku_statuses_id'], $new_items['currencies_id'], $new_items['purchase_price'], $new_items['ttp'], $new_items['landed_cost'], $new_items['moq_store'], $new_items['full_item_description'], $new_items['created_by'], $new_items['updated_by']]);
                        //-------------------------                         
			            
						foreach($this->segments as $segment)
                        {
							    //--added by cris 20200629---
                                    if ($new_items[$segment->segment_column_name] == "X" || $new_items[$segment->segment_column_name] == null) {
                                    } else {
                    
                                        $segmentation = DB::connection('mysql_trs')->table('segmentation')->where('segmentation_column1', $segment->segment_column_name)
                                            ->orWhere('segmentation_column2', $segment->segment_column_name)->first();
                    
                                        $store_ids = DB::connection('mysql_trs')->table('stores')->select('id')->where('store_code', $segmentation->segmentation_code)->get()->toArray();
                                        
                                        $store_array = array();
                                        foreach ($store_ids as $id) {
                                            array_push($store_array, $id->id);
                                        }

                                        $stores = array();
                                        $trs_users_stores = DB::connection('mysql_trs')->table('cms_users')->select('stores_id', 'id')->where('status', 'ACTIVE')->get();
                                        $superadmin_admin = DB::connection('mysql_trs')->table('cms_users')->select('id')->where('id_cms_privileges', 1) //superAdmin
                                            ->orWhere('id_cms_privileges', 4) //admin
                                            ->where('status', 'ACTIVE')->get()->toArray();
                    
                                        foreach ($trs_users_stores as $value) {
                                            $list = array_map('intval', explode(",", $value->stores_id));
                    
                                            $value->stores_id = $list;
                    
                                            array_push($stores, $value);
                                        }
                    
                                        //check kung may kaparehas sa stores_id
                                        $id_to_send = array();
                                        foreach ($stores as $store) {
                    
                                            for ($i = 0; $i <= count($store->stores_id); $i++) {
                    
                                                if (in_array($store->stores_id[$i], $store_array)) {
                                                    array_push($id_to_send, $store->id);
                                                }
                                            }
                                        }
                    
                                        //send notification
                                        foreach ($id_to_send as $id) {
                                            $content = " New item code ".$new_items->item. " has been added in ".$segmentation->segmentation_code. " at " . date('Y-m-d H:i:s');
                                            $to = "https://replenishment.tasteless.com.ph/public/admin/items?q=".$new_items->tasteless_code;
                                            
                    
                                            DB::connection('mysql_trs')->statement('insert into cms_notifications (id_cms_users,content,url,created_at,is_read) VALUES (?,?,?,?,?)', [$id, $content, $to, date('Y-m-d H:i:s'), 0]);
                                        }
										
                                        foreach ($superadmin_admin as $id) {
                                            $content = "New item code ".$new_items->item. " has been added in ".$segmentation->segmentation_code. " at " . date('Y-m-d H:i:s');
                                            $to = "https://replenishment.tasteless.com.ph/public/admin/items?q=".$new_items->tasteless_code;
                    
                                            DB::connection('mysql_trs')->statement('insert into cms_notifications (id_cms_users,content,url,created_at,is_read) VALUES (?,?,?,?,?)', [$id->id, $content, $to, date('Y-m-d H:i:s'), 0]);
                                        }
                                    }
                                    //----------------------------
							    
                               $sku_value = "'".$new_items[$segment->segment_column_name]."'";
                                            
                                DB::connection('mysql_trs')->statement('update items set '.$segment->segment_column_name.' = '.$sku_value.' where tasteless_code = '.$new_items['tasteless_code'].'');
						}
						
						//--added by cris 20200629---
                        unset($store_array);
                        unset($stores);
                        unset($id_to_send);
                        //---------------------------
										
                        DB::disconnect('mysql_trs');
                        
                        CRUDBooster::redirect(CRUDBooster::mainpath(),"Your item has been created successfully .","info");
            }else{   
			    $for_approval = ItemMasterApproval::where('id',$id)->first();
			    $approvers = ApprovalWorkflowSetting::where('status','ACTIVE')->where('action_type','Create')
							->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->get();

			    foreach ($approvers as $approvers_list){
				    $approver_privilege_for =	DB::table('cms_privileges')->where('id',$approvers_list->encoder_privilege_id)->first();
				    $approver_privilege =		DB::table('cms_privileges')->where('id',$approvers_list->approver_privilege_id)->first();	
			
				    if($for_approval->encoder_privilege_id == $approver_privilege_for->id){
					    $send_to =	DB::table('cms_users')->where('id_cms_privileges',$approver_privilege->id)->get();
					    foreach ($send_to as $send_now){
						    $config['content'] = "An item has been created at Item Masterfile Module, please check item for approval!";
						    $config['to'] = CRUDBooster::adminPath('item_approval?q='.$for_approval->id);
						    $config['id_cms_users'] = [$send_now->id];
						    CRUDBooster::sendNotification($config);	
					    }
				    }
			    }
			    CRUDBooster::redirect(CRUDBooster::mainpath(),"Your item has been created and pending for approval.","info");
            }
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) 
        {
			$ItemValue = DB::table('item_masters')->where('id', $id)->first();
			$CheckTableColumn = Schema::getColumnListing('item_masters');
			
			$ItemValueArray = [];
			foreach($CheckTableColumn as $ctcname => $ctc){
				if(!empty($postdata[$ctc]) && $ctc != "updated_at"){
					if($ctc == "tax_status"){
						$keyname = "tax_codes_id";
					}else{
						$keyname = $ctc;
					}
	
					if($postdata[$keyname] != $ItemValue->$keyname){
						array_push($ItemValueArray, ['name' => $keyname, 'old' => $ItemValue->$keyname, 'new' => $postdata[$keyname]]);
					}
				}
			}

			if(count($ItemValueArray) > 0){
				$DetailsOfItem = '<table class="table table-striped"><thead><tr><th>Column Name</th><th>Old Value</th><th>New Value</th></thead><tbody>';
				foreach ($ItemValueArray as $key => $ItemVal) {
					$DetailsOfItem .= "<tr><td>".$ItemVal['name']."</td><td>".$ItemVal['old']."</td><td>".$ItemVal['new']."</td></tr>";
				}
				$DetailsOfItem .= '</tbody></table>';

                if(!empty($ItemValue->brands_id)){
					$brand_id = $ItemValue->brands_id;
				}else{
					$brand_id = $postdata['brands_id'];
				}

				DB::table('history_item_masterfile')->insert([
					'tasteless_code'	=>	$ItemValue->tasteless_code,
					'item_id'			=>	$id,
					'brand_id'			=>	$brand_id,
					'group_id'			=>	$ItemValue->groups_id,
					'action'			=>	"Update",
					'ttp'               => $postdata['ttp'],
					'ttp_percentage'   => $postdata['ttp_percentage'],
					'old_ttp'           => $ItemValue->ttp,
					'old_ttp_percentage' => $ItemValue->ttp_percentage,
					'purchase_price'    => $postdata['purchase_price'],
					'old_purchase_price' => $ItemValue->purchase_price,
					'details'			=>	$DetailsOfItem,
					'created_by'		=>	$ItemValue->created_by,
					'updated_by'		=>	CRUDBooster::myId()
				]);
			}
			//********************************************************************* */
  
			//Your code here
			if(CRUDBooster::isSuperadmin() || CRUDBooster::myPrivilegeId() == 8 || CRUDBooster::myPrivilegeId() == 13) // 	Manager (Purchaser) //View III (TTP and Purchase Price) 	
            {
			    //---added by cris 20200623------
                $crnt_ttp_price = DB::table('item_masters')->where('id', $id)->first();
    
                $this->counter = 0;
                $this->pre_ttp_price = 0;
                $this->pre_ttp_price = $crnt_ttp_price->ttp;
                unset($this->segmentation_editForm);
                //-------------------------------
			    
                $postdata["encoder_privilege_id"] =	CRUDBooster::myPrivilegeId();
			    $postdata["action_type"] = "Update";
			    $postdata["updated_by"] = CRUDBooster::myId();
			    
	            $sku_legend = Input::all();
	            
	            //-----added by cris 20201009--------
                $postdata["packagings_id"] = $sku_legend['uoms_set_id'];
                //-----------------------------------
                
	            //-----added by cris 20200707-------------------
                $postdata["tax_status"] = $sku_legend['tax_codes_id'];
                //----------------------------------------------
                
				
				 //-----added by cris 20200707-------------------
                $this->segmentation_editForm = [];
                //----------------------------------------------
				
				DB::connection('mysql_trs')->table('items')->where('tasteless_code',$postdata["tasteless_code"])->update([
					'supplier_item_code' 	=> $sku_legend['supplier_item_code'],
					'full_item_description' => $sku_legend['full_item_description'],
					'myob_item_description' => $sku_legend['full_item_description'],
					'brand_id' 				=> $sku_legend['brands_id'],
					'group_id' 				=> $sku_legend['groups_id'],
                    'fulfillment_types_id'   => $sku_legend['fulfillment_types_id'],
					'category_id' 			=> $sku_legend['categories_id'],
					'subcategory_id' 		=> $sku_legend['subcategories_id'],
					'uom_id' 				=> $sku_legend['uoms_id'],
					'packaging_id' 			=> $sku_legend['uoms_set_id'],
					'skustatus_id' 			=> $sku_legend['sku_statuses_id'],
					'currency_id' 			=> $sku_legend['currencies_id'],
					'cost_price' 			=> $sku_legend['purchase_price'],
					'ttp' 					=> $sku_legend['ttp'],
					'landed_cost' 			=> $sku_legend['landed_cost'],
					'moq_supplier' 			=> $postdata['moq_supplier'],
					'moq_store' 			=> $sku_legend['moq_store'],
					'updated_by' 			=> CRUDBooster::myId(),
					'updated_at' 			=> date('Y-m-d H:i:s'),
				]);

				foreach($this->segments as $segment)
				{
					$segment_search = $sku_legend[$segment->segment_column_name];
				
					ItemMasterApproval::where('id',$id)->update([
						$segment->segment_column_name => $segment_search
					]);
					
					ItemMaster::where('id',$id)->update([
						$segment->segment_column_name => $segment_search
					]);
			
					DB::connection('mysql_trs')->table('items')->where('tasteless_code','=',(string)$postdata["tasteless_code"])->update([
                        $segment->segment_column_name => $segment_search
                    ]);
					
					//->statement('update items set '.$segment->segment_column_name.' = '.$var.' where tasteless_code = '.$postdata["tasteless_code"].'');
				
					//-----added by cris 20200707-------------------
					if ($segment_search == "X" || $segment_search == null) {
					}else{
						$this->counter = 1;
						array_push($this->segmentation_editForm, $segment->segment_column_name);
					}
					//----------------------------------------------
				}
			    
			    DB::disconnect('mysql_trs');
			    
			}else{
			    
                // Fullfillment Type
			    if(!empty($postdata['fulfillment_types_id']))
			    {
			        // TIMFS item_masters table
			        DB::table('item_masters')->where('tasteless_code',$postdata["tasteless_code"])->update([
					    'fulfillment_types_id'   =>  $postdata['fulfillment_types_id']
				    ]);
				    
				    // TRS items table
				    DB::connection('mysql_trs')->table('items')->where('tasteless_code',$postdata["tasteless_code"])->update([
                        'fulfillment_types_id'   =>  $postdata['fulfillment_types_id']
                    ]);
			    }

			    $item_status = "";
		    	$encoder_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->where('action_type', 'Update')
								->where('encoder_privilege_id',CRUDBooster::myPrivilegeId())
								->where('status','ACTIVE')->first();

			    switch ($encoder_checker->workflow_number){
				case '4' :

					$item_status =	ApprovalWorkflowSetting::where('workflow_number', 4)->where('action_type', 'Update')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('current_state');
				
					ItemMasterApproval::where('id',$id)->update([
						'chart_accounts_id' 			=> $postdata['chart_accounts_id'],
						'updated_by'					=> CRUDBooster::myId(),
						'encoder_privilege_id'			=> CRUDBooster::myPrivilegeId(),
						'action_type'					=> 'Update',
						'approval_status'				=> $item_status
					]);
				break;

				default:
				
					foreach($this->segments as $segment){					
						ItemMasterApproval::where('id',$id)->update([
                            $segment->segment_column_name => $postdata[$segment->segment_column_name]
						]);
					}

					$item_status = 	ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('current_state');

					ItemMasterApproval::where('id',$id)->update([
						'tasteless_code' 				=> $postdata['tasteless_code'],
						'suppliers_id' 					=> $postdata['suppliers_id'],
						'trademarks_id' 				=> $postdata['trademarks_id'],
						'classifications_id' 			=> $postdata['classifications_id'],
						'supplier_item_code' 			=> $postdata['supplier_item_code'],
						'myob_item_description' 		=> $postdata['myob_item_description'],
						'full_item_description' 		=> $postdata['full_item_description'],
						'brands_id' 					=> $postdata['brands_id'],
						'groups_id' 					=> $postdata['groups_id'],
						'categories_id' 				=> $postdata['categories_id'],
						'subcategories_id'				=> $postdata['subcategories_id'],
						'types_id'						=> $postdata['types_id'],
						'colors_id'						=> $postdata['colors_id'],
						'actual_color'					=> $postdata['actual_color'],
						'flavor'						=> $postdata['flavor'],
						'packaging_size'				=> $postdata['packaging_size'],
						'packaging_dimension'			=> $postdata['packaging_dimension'],
						'uoms_id' 						=> $postdata['uoms_id'],
						'packagings_id' 				=> $postdata['packagings_id'],
						'vendor_types_id' 				=> $postdata['vendor_types_id'],
						'vendor_types_id' 				=> $postdata['vendor_types_id'],
						'sku_statuses_id' 				=> $postdata['sku_statuses_id'],
						'tax_codes_id' 					=> $postdata['tax_codes_id'],
						'currencies_id' 				=> $postdata['currencies_id'],
						'purchase_price' 				=> $postdata['purchase_price'],
						'ttp' 							=> $postdata['ttp'],
						'ttp_percentage' 				=> $postdata['ttp_percentage'],
						'ttp_price_change' 				=> $postdata['ttp_price_change'],
						'ttp_price_effective_date' 		=> $postdata['ttp_price_effective_date'],
						'landed_cost' 					=> $postdata['landed_cost'],
						'moq_supplier' 					=> $postdata['moq_supplier'],
						'moq_store' 					=> $postdata['moq_store'],
						'segmentation' 					=> $postdata['segmentation'],
						//'chart_accounts_id' 			=> $postdata['chart_accounts_id'],
						'updated_by'					=> CRUDBooster::myId(),
						'encoder_privilege_id'			=> CRUDBooster::myPrivilegeId(),
						'action_type'					=> 'Update',
						'approval_status'				=> $item_status
					]);
				break;	
			}
				
            if(CRUDBooster::myPrivilegeId() == 4){ //Supervisor (Purchaser)
			    foreach($this->segments as $segment)
				{
					$segment_search = $postdata[$segment->segment_column_name];
					
				    if(ItemMasterApproval::where('id',$id)->get()){
				        ItemMasterApproval::where('id',$id)->update([
    						$segment->segment_column_name => $segment_search
    					]);
				    }
					
					ItemMaster::where('id',$id)->update([
						$segment->segment_column_name => $segment_search
					]);

					DB::connection('mysql_trs')->table('items')->where('tasteless_code',$postdata["tasteless_code"])->update([
                        $segment->segment_column_name => $segment_search
                    ]);

					if ($segment_search == "X" || $segment_search == null) {
					}else{
						$this->counter = 1;
						array_push($this->segmentation_editForm, $segment->segment_column_name);
					}
					//----------------------------------------------
				}
				CRUDBooster::redirect(CRUDBooster::mainpath(), trans("Your item has been edited successfully!"), 'success');
			}

			    unset($postdata);
			    unset($this->arr);

			    $this->arr["encoder_privilege_id"]		=	CRUDBooster::myPrivilegeId();
			    $this->arr["action_type"]				=	"Update";
			    $this->arr["updated_by"]				=	CRUDBooster::myId();
			 //   $this->arr['approval_status']			= 	ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('current_state');
		
			}  
		}

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) 
        {
	        //Your code here 
	        if(CRUDBooster::isSuperadmin())
            {
	            	//CRUDBooster::redirect(CRUDBooster::mainpath(),"Your item has been edited successfully .","sucess");
	            	//---added by cris 20200623------
                        if ($this->counter == 1) 
                        {
                            $ttp = DB::table('item_masters')->where('id', $id)->first();
                            if ($this->pre_ttp_price != $ttp->ttp) 
                            {
                                foreach ($this->segmentation_editForm as  $loop) 
                                {            
                                    $segmentation = DB::connection('mysql_trs')->table('segmentation')->where('segmentation_column1', $loop)
                                        ->orWhere('segmentation_column2', $loop)->first();
            
                                    $store_ids = DB::connection('mysql_trs')->table('stores')->select('id')->where('store_code', $segmentation->segmentation_code)->get()->toArray();
            
                                    // dd("ey");
                                    $store_array = array();
                                    foreach ($store_ids as $id) {
                                        array_push($store_array, $id->id);
                                    }
                                    $stores = array();
                                    $trs_users_stores = DB::connection('mysql_trs')->table('cms_users')->select('stores_id', 'id')->where('status', 'ACTIVE')->get();
                                    $superadmin_admin = DB::connection('mysql_trs')->table('cms_users')->select('id')->where('id_cms_privileges', 1) //superAdmin
                                        ->orWhere('id_cms_privileges', 4) //admin
                                        ->where('status', 'ACTIVE')->get()->toArray();
            
                                    foreach ($trs_users_stores as $value) {
                                        // array_push($stores, $value->stores_id);
                                        $list = array_map('intval', explode(",", $value->stores_id));
            
                                        $value->stores_id = $list;
            
                                        array_push($stores, $value);
                                    }
            
                                    //check kung may kaparehas sa stores_id
                                    $id_to_send = array();
                                    foreach ($stores as $store) {
            
                                        for ($i = 0; $i <= count($store->stores_id); $i++) {
            
                                            if (in_array($store->stores_id[$i], $store_array)) {
                                                array_push($id_to_send, $store->id);
                                            }
                                        }
                                    }
            
                                    //send notification
                                    foreach ($id_to_send as $id) {
                                        $content = " Item has been edited successfully with price change with Tasteless code " . $ttp->tasteless_code . " at " . date('Y-m-d H:i:s');
                                        $to = "https://replenishment.tasteless.com.ph/public/admin/items?q=".$ttp->tasteless_code;
            
                                        DB::connection('mysql_trs')->statement('insert into cms_notifications (id_cms_users,content,url,created_at,is_read) VALUES (?,?,?,?,?)', [$id, $content, $to, date('Y-m-d H:i:s'), 0]);
                                    }
            
                                    foreach ($superadmin_admin as $id) {
                                        $content = " Item has been edited successfully with price change with Tasteless code " . $ttp->tasteless_code . " in store code " . $segmentation->segmentation_code . " at " . date('Y-m-d H:i:s');
                                        $to = "https://replenishment.tasteless.com.ph/public/admin/items?q=".$ttp->tasteless_code;
            
                                        DB::connection('mysql_trs')->statement('insert into cms_notifications (id_cms_users,content,url,created_at,is_read) VALUES (?,?,?,?,?)', [$id->id, $content, $to, date('Y-m-d H:i:s'), 0]);
                                    }
                                }
            
                                CRUDBooster::redirect(CRUDBooster::mainpath(), trans("Your item has been edited successfully with price change!"), 'success');
                            }
                            CRUDBooster::redirect(CRUDBooster::mainpath(), trans("Your item has been edited successfully!"), 'success');
                        } else {
                            CRUDBooster::redirect(CRUDBooster::mainpath(), trans("Your item has been edited successfully!"), 'success');
                        }
            
                        unset($this->segmentation_editForm);
                        unset($store_array);
                        unset($stores);
                        unset($id_to_send);
	            	
	        }else{
			$for_approval = ItemMasterApproval::where('id',$id)->first();
			$approvers = 	ApprovalWorkflowSetting::where('status','ACTIVE')->where('action_type', 'Update')
							->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->get();

			foreach ($approvers as $approvers_list){
				$approver_privilege_for =	DB::table('cms_privileges')->where('id',$approvers_list->encoder_privilege_id)->first();
				$approver_privilege =		DB::table('cms_privileges')->where('id',$approvers_list->approver_privilege_id)->first();	
			
				if($for_approval->encoder_privilege_id == $approver_privilege_for->id){
					$send_to =	DB::table('cms_users')->where('id_cms_privileges',$approver_privilege->id)->get();
					foreach ($send_to as $send_now){
						$config['content'] = "An item has been edited at Item Masterfile Module, please check item for approval!";
						$config['to'] = CRUDBooster::adminPath('item_approval?q='.$for_approval->tasteless_code);
						$config['id_cms_users'] = [$send_now->id];
						CRUDBooster::sendNotification($config);	
					}
				}
				
			}
// 			CRUDBooster::redirect(CRUDBooster::mainpath(),"Your item has been edited and pending for approval.","info");
			CRUDBooster::redirect(CRUDBooster::mainpath(), trans("Your item has been edited successfully!"), 'success');
	        }
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) 
        { 
			$deletedItem = ItemMaster::where('id', $id)->first();

			DB::table('history_item_masterfile')->insert([
				'tasteless_code'	=>	$deletedItem->tasteless_code,
				'item_id'			=>	$id,
				'brand_id'			=>	$deletedItem->brands_id,
				'group_id'			=>	$deletedItem->groups_id,
				'action'			=>	"Delete",
				'details'			=>	'This item is deleted at '.date("Y-m-d H:i:s"),
				'created_by'		=>	$deletedItem->created_by,
				'updated_by'		=>	CRUDBooster::myId()
			]);
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

		public function getBrandData($id) {
			
			$brand = Brand::where('id', $id)->first();

			return response()->json($brand);
		}

		public function getEdit($id){

			$item_info = ItemMasterApproval::find($id);

			$item_update_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('current_state');
			$item_update_status1 = ApprovalWorkflowSetting::where('workflow_number', 2)->where('action_type', 'Update')->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->value('current_state');
			
			if($item_info->approval_status == $item_update_status || $item_info->item_update_status1 == $item_update_status ){
				CRUDBooster::redirect(CRUDBooster::mainpath(""),"You're not allowed to edit pending items for approval.","warning");
			}

			$editform_approval_views = DB::table('settings_form_accesses')->where('cms_privileges_id',$priv->id)->where('action_type','EDIT APPROVAL')->first();

			return parent::getEdit($id);
		}

		public function exportItems(Request $request)
		{
			$filename = $request->input('filename');
			return Excel::download(new ItemExport, $filename.'.xlsx');
		}
		
		public function exportQBFormat(Request $request)
		{
			$filename = $request->input('filename');
			return Excel::download(new QBExport, $filename.'.xlsx');
		}

		public function exportPOSFormat(Request $request) {
			$filename = $request->input('filename');
		   	return Excel::download(new POSExport, $filename.'.xlsx');
		}

		public function exportBartender(Request $request) {
			$filename = $request->input('filename');
		   	return Excel::download(new BartenderExport, $filename.'.xlsx');
		}		
		
		public function getUploadModule(){
			$this->cbLoader();
			$data['page_title'] = 'Upload Module';
			return view("upload.upload", $data);
		}
	}
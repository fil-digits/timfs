<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\ItemMaster;
	use App\ItemMasterApproval;
	use App\ApprovalWorkflowSetting;
	use App\Group;
	use App\CodeCounter;
	use App\HistoryLandedCost;
	use App\HistoryPurchasePrice;
	use App\HistoryTtp;
	use Illuminate\Support\Facades\Input;

	class AdminItemApprovalController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->button_add = false;
			$this->button_edit = true;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "item_master_approvals";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			//----added by cris 20200630
        $this->col[] = ["label" => "Action Type", "name" => "action_type"];
        $this->col[] = ["label" => "Approval Status", "name" => "approval_status"];
        $this->col[] = ["label" => "Tasteless Code", "name" => "tasteless_code"];
        $this->col[] = ["label" => "Type", "name" => "types_id", "join" => "types,type_description", "visible" => CRUDBooster::myColumnView()->type ? true : false];
        $this->col[] = ["label" => "Item", "name" => "item", "visible" =>  true];
        $this->col[] = ["label" => "Description", "name" => "full_item_description", "visible" => CRUDBooster::myColumnView()->full_item_description ? true : false];
        $this->col[] = ["label" => "Tax Code", "name" => "tax_codes_id", "join" => "tax_codes,tax_code", "visible" => CRUDBooster::myColumnView()->tax_code ? true : false];
        $this->col[] = ["label" => "Account", "name" => "accounts_id", "join" => "accounts,group_description", "visible" =>  true];
        $this->col[] = ["label" => "COGS Account", "name" => "cogs_accounts_id", "join" => "cogs_accounts,group_description", "visible" =>  true];
        $this->col[] = ["label" => "Asset Account", "name" => "asset_accounts_id", "join" => "asset_accounts,group_description", "visible" =>  true];
        $this->col[] = ["label" => "Accumulated Depreciation", "name" => "accumulated_depreciation", "visible" =>  true];
        $this->col[] = ["label" => "Purchase Description", "name" => "purchase_description", "visible" =>  true];
        $this->col[] = ["label" => "Quantity On Hand", "name" => "quantity_on_hand", "visible" =>  true];
        $this->col[] = ["label" => "Fulfillment Type", "name" => "fulfillment_type_id", "join" => "fulfillment_methods,fulfillment_method"];
        $this->col[] = ["label" => "UOM", "name" => "uoms_id", "join" => "uoms,uom_description", "visible" => CRUDBooster::myColumnView()->uom ? true : false];
        $this->col[] = ["label" => "UOM Set", "name" => "uoms_set_id", "join" => "uoms_set,uom_description", "visible" =>  true];
        $this->col[] = ["label" => "Cost", "name" => "purchase_price", "visible" => CRUDBooster::myColumnView()->purchase_price ? true : false];
        $this->col[] = ["label" => "TTP", "name" => "ttp", "visible" => CRUDBooster::myColumnView()->ttp ? true : false];
        $this->col[] = ["label" => "Commi Margin", "name" => "ttp_percentage", "visible" => CRUDBooster::myColumnView()->ttp_percentage ? true : false];
        $this->col[] = ["label" => "Landed Cost", "name" => "landed_cost", "visible" => CRUDBooster::myColumnView()->landed_cost ? true : false];
        $this->col[] = ["label" => "Preffered Vendor", "name" => "suppliers_id", "join" => "suppliers,last_name", "visible" => CRUDBooster::myColumnView()->supplier ? true : false];
        $this->col[] = ["label" => "Tax Agency", "name" => "tax_agency", "visible" =>  true];
        $this->col[] = ["label" => "Reorder Pt (Min)", "name" => "reorder_pt", "visible" =>  true];
        $this->col[] = ["label" => "MPN", "name" => "mpn", "visible" =>  true];
        $this->col[] = ["label" => "Group", "name" => "groups_id", "join" => "groups,group_description", "visible" => CRUDBooster::myColumnView()->group ? true : false];
        $this->col[] = ["label" => "Category Description", "name" => "categories_id", "join" => "categories,category_description", "visible" => CRUDBooster::myColumnView()->category_description ? true : false];
        $this->col[] = ["label" => "Subcategory Description", "name" => "subcategories_id", "join" => "subcategories,subcategory_description", "visible" => CRUDBooster::myColumnView()->subcategory ? true : false];
        $this->col[] = ["label" => "Dimension", "name" => "packaging_dimension", "visible" => CRUDBooster::myColumnView()->packaging_dimension ? true : false];
        $this->col[] = ["label" => "Packaging Size", "name" => "packaging_size", "visible" => CRUDBooster::myColumnView()->packaging_size ? true : false];
        $this->col[] = ["label" => "Packaging UOM", "name" => "packagings_id", "join" => "packagings,packaging_description", "visible" => CRUDBooster::myColumnView()->packaging ? true : false];
        $this->col[] = ["label" => "Tax Status", "name" => "tax_codes_id", "join" => "tax_codes,tax_code", "visible" => CRUDBooster::myColumnView()->tax_code ? true : false];
        // $this->col[] = ["label" => "Price", "name" => "price", "visible" =>  true];
        $this->col[] = ["label" => "Supplier Item Code", "name" => "supplier_item_code", "visible" => CRUDBooster::myColumnView()->supplier_item_code ? true : false];
        $this->col[] = ["label" => "MOQ Store", "name" => "moq_store", "visible" => CRUDBooster::myColumnView()->moq_store ? true : false];
        $this->col[] = ["label" => "Account Number", "name" => "chart_accounts_id", "join" => "chart_accounts,account_number", "visible" => CRUDBooster::myColumnView()->chart_accounts ? true : false];
        $this->col[] = ["label" => "Created Date", "name" => "created_at", "visible" => CRUDBooster::myColumnView()->create_date ? true : false];
        $this->col[] = ["label" => "Created By", "name" => "created_by", "join" => "cms_users,name", "visible" => CRUDBooster::myColumnView()->create_by ? true : false];
        $this->col[] = ["label" => "Updated Date", "name" => "updated_at", "visible" => CRUDBooster::myColumnView()->update_date ? true : false];
        $this->col[] = ["label" => "Updated By", "name" => "updated_by", "join" => "cms_users,name", "visible" => CRUDBooster::myColumnView()->update_date ? true : false];
        //--------------------------

        // hided by cris
        //$this->col[] = ["label"=>"Item ID","name"=>"id","visible" => false];

        // 			$this->col[] = ["label"=>"Action Type","name"=>"action_type"];
        // 			$this->col[] = ["label"=>"Approval Status","name"=>"approval_status"];
        // 			$this->col[] = ["label"=>"Tasteless Code","name"=>"tasteless_code"];
        // 			$this->col[] = ["label"=>"Co. Last Name","name"=>"suppliers_id","join"=>"suppliers,last_name","visible" => CRUDBooster::myColumnView()->supplier ? true : false];
        // 			$this->col[] = ["label"=>"Co. First Name ","name"=>"suppliers_id","join"=>"suppliers,first_name","visible" => CRUDBooster::myColumnView()->supplier ? true : false];
        // 			$this->col[] = ["label"=>"Trade Name","name"=>"trademarks_id","join"=>"trademarks,trademark","visible" => CRUDBooster::myColumnView()->trademark ? true : false];
        // 			$this->col[] = ["label"=>"Classification","name"=>"classifications_id","join"=>"classifications,classification_name","visible" => CRUDBooster::myColumnView()->classification ? true : false];
        // 			$this->col[] = ["label"=>"Supplier Item Code","name"=>"supplier_item_code","visible" => CRUDBooster::myColumnView()->supplier_item_code ? true : false];
        // 			$this->col[] = ["label"=>"MYOB Item Description","name"=>"myob_item_description","visible" => CRUDBooster::myColumnView()->myob_item_description ? true : false];
        // 			$this->col[] = ["label"=>"Full Item Description","name"=>"full_item_description","visible" => CRUDBooster::myColumnView()->full_item_description ? true : false];
        // 			$this->col[] = ["label"=>"Brand Code","name"=>"brands_id","join"=>"brands,brand_code","visible" => CRUDBooster::myColumnView()->brand_code ? true : false];
        // 			$this->col[] = ["label"=>"Brand Description","name"=>"brands_id","join"=>"brands,brand_description","visible" => CRUDBooster::myColumnView()->brand_description ? true : false];
        // 			$this->col[] = ["label"=>"Group","name"=>"groups_id","join"=>"groups,group_description","visible" => CRUDBooster::myColumnView()->group ? true : false];
        // 			$this->col[] = ["label"=>"Category Code","name"=>"categories_id","join"=>"categories,category_code","visible" => CRUDBooster::myColumnView()->category_code ? true : false];
        // 			$this->col[] = ["label"=>"Category Description","name"=>"categories_id","join"=>"categories,category_description","visible" => CRUDBooster::myColumnView()->category_description ? true : false];
        // 			$this->col[] = ["label"=>"Subcategory Description","name"=>"subcategories_id","join"=>"subcategories,subcategory_description","visible" => CRUDBooster::myColumnView()->subcategory ? true : false];
        // 			$this->col[] = ["label"=>"Type","name"=>"types_id","join"=>"types,type_description","visible" => CRUDBooster::myColumnView()->type ? true : false];
        // 			$this->col[] = ["label"=>"Color Code","name"=>"colors_id","join"=>"colors,color_code","visible" => CRUDBooster::myColumnView()->color_code ? true : false];
        // 			$this->col[] = ["label"=>"Color Description","name"=>"colors_id","join"=>"colors,color_description","visible" => CRUDBooster::myColumnView()->color_description ? true : false];
        // 			$this->col[] = ["label"=>"Actual Color","name"=>"actual_color","visible" => CRUDBooster::myColumnView()->actual_color ? true : false];
        // 			$this->col[] = ["label"=>"Dimension","name"=>"packaging_dimension","visible" => CRUDBooster::myColumnView()->packaging_dimension ? true : false];
        // 			$this->col[] = ["label"=>"Packaging Size","name"=>"packaging_size","visible" => CRUDBooster::myColumnView()->packaging_size ? true : false];
        // 			$this->col[] = ["label"=>"UOM","name"=>"uoms_id","join"=>"uoms,uom_description","visible" => CRUDBooster::myColumnView()->uom ? true : false];
        // 			$this->col[] = ["label"=>"Packaging","name"=>"packagings_id","join"=>"packagings,packaging_description","visible" => CRUDBooster::myColumnView()->packaging ? true : false];
        // 			$this->col[] = ["label"=>"Vendor Type","name"=>"vendor_types_id","join"=>"vendor_types,vendor_type_description","visible" => CRUDBooster::myColumnView()->vendor_type ? true : false];
        // 			$this->col[] = ["label"=>"Inventory Type","name"=>"inventory_types_id","join"=>"inventory_types,inventory_type_description","visible" => CRUDBooster::myColumnView()->inventory_type ? true : false];
        // 			$this->col[] = ["label"=>"SKU Status","name"=>"sku_statuses_id","join"=>"sku_statuses,sku_status_description","visible" => CRUDBooster::myColumnView()->sku_status ? true : false];
        // 			$this->col[] = ["label"=>"Tax Code","name"=>"tax_codes_id","join"=>"tax_codes,tax_code","visible" => CRUDBooster::myColumnView()->tax_code ? true : false];
        // 			$this->col[] = ["label"=>"Currency","name"=>"currencies_id","join"=>"currencies,currency_code","visible" => CRUDBooster::myColumnView()->currency ? true : false];
        // 			$this->col[] = ["label"=>"Purchase Price","name"=>"purchase_price","visible" => CRUDBooster::myColumnView()->purchase_price ? true : false];
        // 			$this->col[] = ["label"=>"TTP","name"=>"ttp","visible" => CRUDBooster::myColumnView()->ttp ? true : false];
        // 			$this->col[] = ["label"=>"TTP Percentage","name"=>"ttp_percentage","visible" => CRUDBooster::myColumnView()->ttp_percentage ? true : false];
        // 			$this->col[] = ["label"=>"Landed Cost","name"=>"landed_cost","visible" => CRUDBooster::myColumnView()->landed_cost ? true : false];
        // 			$this->col[] = ["label"=>"MOQ Supplier","name"=>"moq_supplier","visible" => CRUDBooster::myColumnView()->moq_supplier ? true : false];
        // 			$this->col[] = ["label"=>"MOQ Store","name"=>"moq_store","visible" => CRUDBooster::myColumnView()->moq_store ? true : false];
        // 			$this->col[] = ["label"=>"Account Number","name"=>"chart_accounts_id","join"=>"chart_accounts,account_number","visible" => CRUDBooster::myColumnView()->chart_accounts ? true : false];

        //             $this->col[] = ["label" => "Created Date", "name" => "created_at","visible" => CRUDBooster::myColumnView()->create_date ? true : false];
        //             $this->col[] = ["label" => "Created By", "name" => "created_by", "join" => "cms_users,name","visible" => CRUDBooster::myColumnView()->create_by ? true : false];
        //             $this->col[] = ["label" => "Updated Date", "name" => "updated_at","visible" => CRUDBooster::myColumnView()->update_date ? true : false];
        //             $this->col[] = ["label" => "Updated By", "name" => "updated_by", "join" => "cms_users,name","visible" => CRUDBooster::myColumnView()->update_date ? true : false];
        # END COLUMNS DO NOT REMOVE THIS LINE

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = [];
        //----added by cris 20200630
        if (in_array(CRUDBooster::getCurrentMethod(), ['getAdd', 'postAddSave'])) {

            //$this->form[] = ['label'=>'Tasteless Code','name'=>'tasteless_code','type'=>'text','validation'=>'required|min:10|max:10','width'=>'col-sm-4'];

            $this->form[] = [
                'label' => 'Type', 'name' => 'types_id', 'type' => 'select2',
                'validation' => CRUDBooster::myAddForm()->type ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                'datatable' => 'types,type_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->type ?: 'display:none;'
            ];


            $this->form[] = [
                'label' => 'Item', 'name' => 'item', 'type' => 'text',
                'validation' => 'required', 'width' => 'col-sm-4'
            ];

            $this->form[] = [
                'label' => 'Description', 'name' => 'full_item_description', 'type' => 'text',
                'validation' => CRUDBooster::myAddForm()->full_item_description ? 'required|min:5|max:255' : '', 'width' => 'col-sm-4',
                'style' => CRUDBooster::myAddForm()->full_item_description ?: 'display:none;'
            ];

            $this->form[] = [
                'label' => 'Tax Code', 'name' => 'tax_codes_id', 'type' => 'select2',
                'validation' => CRUDBooster::myAddForm()->tax_code ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                'datatable' => 'tax_codes,tax_code', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->tax_code ?: 'display:none;'
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
                'label' => 'Accumulated Depreciation', 'name' => 'accumulated_depreciation', 'type' => 'number',
                'validation' => 'min:0.00', 'width' => 'col-sm-4'
            ];

            $this->form[] = [
                'label' => 'Purchase Description', 'name' => 'purchase_description', 'type' => 'text',
                'validation' => 'required|min:5|max:255', 'width' => 'col-sm-4', 'readonly' => true
            ];

            $this->form[] = [
                'label' => 'Quantity On Hand', 'name' => 'quantity_on_hand', 'type' => 'number',
                'validation' => 'min:0.00', 'width' => 'col-sm-4'
            ];

            $this->form[] = [
				'label' => 'Fulfillment Type', 'name' => 'fulfillment_type_id', 'type' => 'select2',
				'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
				'datatable' => 'fulfillment_methods,fulfillment_method', 'datatable_where' => "status='ACTIVE'"
			];

            $this->form[] = [
                'label' => 'UOM', 'name' => 'uoms_id', 'type' => 'select2',
                'validation' => CRUDBooster::myAddForm()->uom ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                'datatable' => 'uoms,uom_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->uom ?: 'display:none;'
            ];

            $this->form[] = [
                'label' => 'UOM Set', 'name' => 'uoms_set_id', 'type' => 'select2',
                'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
                'datatable' => 'uoms_set,uom_description', 'datatable_where' => "status='ACTIVE'"
            ];

            // $this->form[] = ['label'=>'Currency','name'=>'currencies_id','type'=>'select2',
            // 'validation'=>CRUDBooster::myAddForm()->currency ? 'required|integer|min:0' : '','width'=>'col-sm-4',
            // 'datatable'=>'currencies,currency_code','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->currency ? : 'display:none;'];

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
                'label' => 'Preferred Vendor', 'name' => 'suppliers_id', 'type' => 'select2',
                'disabled' => CRUDBooster::myEditReadOnly()->supplier ? true : false,
                'validation' => CRUDBooster::myEditForm()->supplier ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                'datatable' => 'suppliers,last_name', 'style' => CRUDBooster::myAddForm()->supplier ?: 'display:none;'
            ];

            $this->form[] = ['label' => 'Tax Agency', 'name' => 'tax_agency', 'type' => 'text', 'width' => 'col-sm-4'];



            $this->form[] = [
                'label' => 'Reorder Pt (Min)', 'name' => 'reorder_pt', 'type' => 'number',
                'validation' => 'min:0.00', 'width' => 'col-sm-4'
            ];

            $this->form[] = ['label' => 'MPN', 'name' => 'mpn', 'type' => 'text', 'width' => 'col-sm-4'];

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
                'validation' => CRUDBooster::myAddForm()->packaging_dimension ? 'required|max:50' : '', 'width' => 'col-sm-4',
                'style' => CRUDBooster::myAddForm()->packaging_dimension ?: 'display:none;'
            ];

            $this->form[] = [
                'label' => 'Packaging Size', 'name' => 'packaging_size', 'type' => 'number',
                'validation' => CRUDBooster::myAddForm()->packaging_size ? 'required' : '', 'width' => 'col-sm-4',
                'style' => CRUDBooster::myAddForm()->packaging_size ?: 'display:none;'
            ];

            $this->form[] = [
                'label' => 'Cost', 'name' => 'cost', 'type' => 'number',
                'validation' => 'required', 'width' => 'col-sm-4', 'readonly' => true
            ];

            $this->form[] = [
                'label' => 'Packaging UOM', 'name' => 'packagings_id', 'type' => 'select2',
                'validation' => CRUDBooster::myAddForm()->packaging ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                'datatable' => 'packagings,packaging_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->packaging ?: 'display:none;'
            ];

            $this->form[] = [
                'label' => 'Tax Status', 'name' => 'tax_status', 'type' => 'text',
                'validation' => 'required', 'width' => 'col-sm-4', 'readonly' => true
            ];

            $this->form[] = [
                'label' => 'Price', 'name' => 'price', 'type' => 'number',
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
                'label' => 'SKU Status', 'name' => 'sku_statuses_id', 'type' => 'select2',
                'validation' => CRUDBooster::myAddForm()->sku_status ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                'datatable' => 'sku_statuses,sku_status_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->sku_status ?: 'display:none;'
            ];

            $this->form[] = [
                'label' => 'Segmentation', 'name' => 'segmentation', 'type' => 'checkbox-custom',
                'validation' => CRUDBooster::myAddForm()->segmentation ? 'required' : '', 'width' => 'col-sm-6',
                'datatable' => 'segmentations,segment_column_description,segment_column_name', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->segmentation ?: 'display:none;'
            ];
        } elseif (in_array(CRUDBooster::getCurrentMethod(), ['getEdit', 'postEditSave'])) {

            $this->form[] = ['label' => 'Tasteless Code', 'name' => 'tasteless_code', 'type' => 'text', 'readonly' => true, 'width' => 'col-sm-4'];

            $this->form[] = [
                'label' => 'Type', 'name' => 'types_id', 'type' => 'select2',
                'validation' => CRUDBooster::myAddForm()->type ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                'datatable' => 'types,type_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->type ?: 'display:none;'
            ];


            $this->form[] = [
                'label' => 'Item', 'name' => 'item', 'type' => 'text',
                'validation' => 'required', 'width' => 'col-sm-4'
            ];

            $this->form[] = [
                'label' => 'Description', 'name' => 'full_item_description', 'type' => 'text',
                'validation' => CRUDBooster::myAddForm()->full_item_description ? 'required|min:5|max:255' : '', 'width' => 'col-sm-4',
                'style' => CRUDBooster::myAddForm()->full_item_description ?: 'display:none;'
            ];

            $this->form[] = [
                'label' => 'Tax Code', 'name' => 'tax_codes_id', 'type' => 'select2',
                'validation' => CRUDBooster::myAddForm()->tax_code ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                'datatable' => 'tax_codes,tax_code', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->tax_code ?: 'display:none;'
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
                'label' => 'Accumulated Depreciation', 'name' => 'accumulated_depreciation', 'type' => 'number',
                'validation' => 'min:0.00', 'width' => 'col-sm-4'
            ];

            $this->form[] = [
                'label' => 'Purchase Description', 'name' => 'purchase_description', 'type' => 'text',
                'validation' => 'required|min:5|max:255', 'width' => 'col-sm-4', 'readonly' => true
            ];

            $this->form[] = [
                'label' => 'Quantity On Hand', 'name' => 'quantity_on_hand', 'type' => 'number',
                'validation' => 'min:0.00', 'width' => 'col-sm-4'
            ];

            $this->form[] = [
				'label' => 'Fulfillment Type', 'name' => 'fulfillment_type_id', 'type' => 'select2',
				'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
				'datatable' => 'fulfillment_methods,fulfillment_method', 'datatable_where' => "status='ACTIVE'"
			];
    		
            $this->form[] = [
                'label' => 'UOM', 'name' => 'uoms_id', 'type' => 'select2',
                'validation' => CRUDBooster::myAddForm()->uom ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                'datatable' => 'uoms,uom_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->uom ?: 'display:none;'
            ];

            $this->form[] = [
                'label' => 'UOM Set', 'name' => 'uoms_set_id', 'type' => 'select2',
                'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
                'datatable' => 'uoms_set,uom_description', 'datatable_where' => "status='ACTIVE'"
            ];

            // $this->form[] = ['label'=>'Currency','name'=>'currencies_id','type'=>'select2',
            // 'validation'=>CRUDBooster::myAddForm()->currency ? 'required|integer|min:0' : '','width'=>'col-sm-4',
            // 'datatable'=>'currencies,currency_code','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->currency ? : 'display:none;'];

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
                'label' => 'Preferred Vendor', 'name' => 'suppliers_id', 'type' => 'select2',
                'disabled' => CRUDBooster::myEditReadOnly()->supplier ? true : false,
                'validation' => CRUDBooster::myEditForm()->supplier ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                'datatable' => 'suppliers,last_name', 'style' => CRUDBooster::myAddForm()->supplier ?: 'display:none;'
            ];

            $this->form[] = ['label' => 'Tax Agency', 'name' => 'tax_agency', 'type' => 'text', 'width' => 'col-sm-4'];



            $this->form[] = [
                'label' => 'Reorder Pt (Min)', 'name' => 'reorder_pt', 'type' => 'number',
                'validation' => 'min:0.00', 'width' => 'col-sm-4'
            ];

            $this->form[] = ['label' => 'MPN', 'name' => 'mpn', 'type' => 'text', 'width' => 'col-sm-4'];

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
                'validation' => CRUDBooster::myAddForm()->packaging_dimension ? 'required|max:50' : '', 'width' => 'col-sm-4',
                'style' => CRUDBooster::myAddForm()->packaging_dimension ?: 'display:none;'
            ];

            $this->form[] = [
                'label' => 'Packaging Size', 'name' => 'packaging_size', 'type' => 'number',
                'validation' => CRUDBooster::myAddForm()->packaging_size ? 'required' : '', 'width' => 'col-sm-4',
                'style' => CRUDBooster::myAddForm()->packaging_size ?: 'display:none;'
            ];

            $this->form[] = [
                'label' => 'Cost', 'name' => 'cost', 'type' => 'number',
                'validation' => 'required', 'width' => 'col-sm-4', 'readonly' => true
            ];

            $this->form[] = [
                'label' => 'Packaging UOM', 'name' => 'packagings_id', 'type' => 'select2',
                'validation' => CRUDBooster::myAddForm()->packaging ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                'datatable' => 'packagings,packaging_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->packaging ?: 'display:none;'
            ];

            $this->form[] = [
                'label' => 'Tax Status', 'name' => 'tax_status', 'type' => 'text',
                'validation' => 'required', 'width' => 'col-sm-4', 'readonly' => true
            ];

            $this->form[] = [
                'label' => 'Price', 'name' => 'price', 'type' => 'number',
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
                'label' => 'SKU Status', 'name' => 'sku_statuses_id', 'type' => 'select2',
                'validation' => CRUDBooster::myAddForm()->sku_status ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                'datatable' => 'sku_statuses,sku_status_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->sku_status ?: 'display:none;'
            ];

            $this->form[] = [
                'label' => 'Segmentation', 'name' => 'segmentation', 'type' => 'checkbox-custom',
                'disabled' => CRUDBooster::myEditReadOnly()->segmentation ? true : false,
                'validation' => CRUDBooster::myEditForm()->segmentation ? 'required' : '', 'width' => 'col-sm-6',
                'datatable' => 'segmentations,segment_column_description,segment_column_name', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myEditForm()->segmentation ?: 'display:none;'
            ];
        } else {

            $this->form[] = ['label' => 'Tasteless Code', 'name' => 'tasteless_code', 'type' => 'text', 'readonly' => true, 'width' => 'col-sm-4'];
            $this->form[] = [
                'label' => 'Type', 'name' => 'types_id', 'type' => 'select2',
                'validation' => CRUDBooster::myAddForm()->type ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                'datatable' => 'types,type_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->type ?: 'display:none;'
            ];


            $this->form[] = [
                'label' => 'Item', 'name' => 'item', 'type' => 'text',
                'validation' => 'required', 'width' => 'col-sm-4'
            ];

            $this->form[] = [
                'label' => 'Description', 'name' => 'full_item_description', 'type' => 'text',
                'validation' => CRUDBooster::myAddForm()->full_item_description ? 'required|min:5|max:255' : '', 'width' => 'col-sm-4',
                'style' => CRUDBooster::myAddForm()->full_item_description ?: 'display:none;'
            ];

            $this->form[] = [
                'label' => 'Tax Code', 'name' => 'tax_codes_id', 'type' => 'select2',
                'validation' => CRUDBooster::myAddForm()->tax_code ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                'datatable' => 'tax_codes,tax_code', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->tax_code ?: 'display:none;'
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
                'label' => 'Accumulated Depreciation', 'name' => 'accumulated_depreciation', 'type' => 'number',
                'validation' => 'min:0.00', 'width' => 'col-sm-4'
            ];

            $this->form[] = [
                'label' => 'Purchase Description', 'name' => 'purchase_description', 'type' => 'text',
                'validation' => 'required|min:5|max:255', 'width' => 'col-sm-4', 'readonly' => true
            ];

            $this->form[] = [
                'label' => 'Quantity On Hand', 'name' => 'quantity_on_hand', 'type' => 'number',
                'validation' => 'min:0.00', 'width' => 'col-sm-4'
            ];

            $this->form[] = [
				'label' => 'Fulfillment Type', 'name' => 'fulfillment_type_id', 'type' => 'select2',
				'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
				'datatable' => 'fulfillment_methods,fulfillment_method', 'datatable_where' => "status='ACTIVE'"
			];

            $this->form[] = [
                'label' => 'UOM', 'name' => 'uoms_id', 'type' => 'select2',
                'validation' => CRUDBooster::myAddForm()->uom ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                'datatable' => 'uoms,uom_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->uom ?: 'display:none;'
            ];

            $this->form[] = [
                'label' => 'UOM Set', 'name' => 'uoms_set_id', 'type' => 'select2',
                'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
                'datatable' => 'uoms_set,uom_description', 'datatable_where' => "status='ACTIVE'"
            ];

            // $this->form[] = ['label'=>'Currency','name'=>'currencies_id','type'=>'select2',
            // 'validation'=>CRUDBooster::myAddForm()->currency ? 'required|integer|min:0' : '','width'=>'col-sm-4',
            // 'datatable'=>'currencies,currency_code','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->currency ? : 'display:none;'];

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
                'label' => 'Preferred Vendor', 'name' => 'suppliers_id', 'type' => 'select2',
                'disabled' => CRUDBooster::myEditReadOnly()->supplier ? true : false,
                'validation' => CRUDBooster::myEditForm()->supplier ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                'datatable' => 'suppliers,last_name', 'style' => CRUDBooster::myAddForm()->supplier ?: 'display:none;'
            ];

            $this->form[] = ['label' => 'Tax Agency', 'name' => 'tax_agency', 'type' => 'text', 'width' => 'col-sm-4'];



            $this->form[] = [
                'label' => 'Reorder Pt (Min)', 'name' => 'reorder_pt', 'type' => 'number',
                'validation' => 'min:0.00', 'width' => 'col-sm-4'
            ];

            $this->form[] = ['label' => 'MPN', 'name' => 'mpn', 'type' => 'text', 'width' => 'col-sm-4'];

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
                'validation' => CRUDBooster::myAddForm()->packaging_dimension ? 'required|max:50' : '', 'width' => 'col-sm-4',
                'style' => CRUDBooster::myAddForm()->packaging_dimension ?: 'display:none;'
            ];

            $this->form[] = [
                'label' => 'Packaging Size', 'name' => 'packaging_size', 'type' => 'number',
                'validation' => CRUDBooster::myAddForm()->packaging_size ? 'required' : '', 'width' => 'col-sm-4',
                'style' => CRUDBooster::myAddForm()->packaging_size ?: 'display:none;'
            ];

            $this->form[] = [
                'label' => 'Cost', 'name' => 'cost', 'type' => 'number',
                'validation' => 'required', 'width' => 'col-sm-4', 'readonly' => true
            ];

            $this->form[] = [
                'label' => 'Packaging UOM', 'name' => 'packagings_id', 'type' => 'select2',
                'validation' => CRUDBooster::myAddForm()->packaging ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                'datatable' => 'packagings,packaging_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->packaging ?: 'display:none;'
            ];

            $this->form[] = [
                'label' => 'Tax Status', 'name' => 'tax_status', 'type' => 'text',
                'validation' => 'required', 'width' => 'col-sm-4', 'readonly' => true
            ];

            $this->form[] = [
                'label' => 'Price', 'name' => 'price', 'type' => 'number',
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
                'label' => 'SKU Status', 'name' => 'sku_statuses_id', 'type' => 'select2',
                'validation' => CRUDBooster::myAddForm()->sku_status ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
                'datatable' => 'sku_statuses,sku_status_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->sku_status ?: 'display:none;'
            ];

            $this->form[] = ['label' => 'Account Number', 'name' => 'chart_accounts_id', 'type' => 'select2', 'datatable' => 'chart_accounts,account_number', 'width' => 'col-sm-4'];

            $segmentation_data = DB::table('segmentations')->where('status', 'ACTIVE')->orderBy('segment_column_code', 'asc')->get();

            foreach ($segmentation_data as $segment) {

                $this->form[] = ['label' => '+' . " " . $segment->segment_column_description, 'name' => $segment->segment_column_name, 'type' => 'checkbox-custom', 'width' => 'col-sm-4'];
            }
        }

        //------------------------

        //          hided by cris
        // 			if (in_array(CRUDBooster::getCurrentMethod(), ['getAdd','postAddSave'])){

        // 				//$this->form[] = ['label'=>'Tasteless Code','name'=>'tasteless_code','type'=>'text','validation'=>'required|min:10|max:10','width'=>'col-sm-4'];
        // 				$this->form[] = ['label'=>'Company Name','name'=>'suppliers_id','type'=>'select2',
        // 					'validation'=>CRUDBooster::myAddForm()->supplier ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'suppliers,last_name','style'=> CRUDBooster::myAddForm()->supplier ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Trade Name','name'=>'trademarks_id','type'=>'select2',
        // 					'validation'=>CRUDBooster::myAddForm()->trademark ? 'integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'trademarks,trademark','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->trademark ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Classification','name'=>'classifications_id','type'=>'select2',
        // 					'validation'=>CRUDBooster::myAddForm()->classification ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'classifications,classification_name','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->classification ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Supplier Item Code','name'=>'supplier_item_code','type'=>'text',
        // 					'validation'=>CRUDBooster::myAddForm()->supplier_item_code ? 'required|max:50' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myAddForm()->supplier_item_code ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Brand Description','name'=>'brands_id','type'=>'select2',
        // 					'validation'=>CRUDBooster::myAddForm()->brand_description ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'brands,brand_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->brand_description ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'MYOB Item Description','name'=>'myob_item_description','type'=>'text',
        // 					'validation'=>CRUDBooster::myAddForm()->myob_item_description ? 'required|min:5|max:55' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myAddForm()->myob_item_description ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Full Item Description','name'=>'full_item_description','type'=>'text',
        // 					'validation'=>CRUDBooster::myAddForm()->full_item_description ? 'required|min:5|max:255' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myAddForm()->full_item_description ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Group','name'=>'groups_id','type'=>'select2',
        // 					'validation'=>CRUDBooster::myAddForm()->group ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'groups,group_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->group ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Category Description','name'=>'categories_id','type'=>'select',
        // 					'validation'=>CRUDBooster::myAddForm()->category_description ?'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'categories,category_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->category_description ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Subcategory Description','name'=>'subcategories_id','type'=>'select',
        // 					'validation'=>CRUDBooster::myAddForm()->subcategory ?'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'subcategories,subcategory_description','datatable_where'=>"status=%27ACTIVE%27",'parent_select'=>'categories_id','style'=> CRUDBooster::myAddForm()->subcategory ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Type','name'=>'types_id','type'=>'select2',
        // 					'validation'=>CRUDBooster::myAddForm()->type ?'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'types,type_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->type ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Color Description','name'=>'colors_id','type'=>'select2',
        // 					'validation'=>CRUDBooster::myAddForm()->color_description ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'colors,color_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->color_description ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Actual Color','name'=>'actual_color','type'=>'text',
        // 					'validation'=>CRUDBooster::myAddForm()->actual_color ? 'required|max:50' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myAddForm()->actual_color ? : 'display:none;'];

        // 				//$this->form[] = ['label'=>'Flavor','name'=>'flavor','type'=>'text',
        // 				//'validation'=>CRUDBooster::myAddForm()->flavor ? 'required|max:30' : '','width'=>'col-sm-4',
        // 				//'style'=> CRUDBooster::myAddForm()->flavor ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Dimension','name'=>'packaging_dimension','type'=>'number',
        // 					'validation'=>CRUDBooster::myAddForm()->packaging_dimension ? 'required' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myAddForm()->packaging_dimension ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Packaging Size','name'=>'packaging_size','type'=>'text',
        // 					'validation'=>CRUDBooster::myAddForm()->packaging_size ? 'required|max:30' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myAddForm()->packaging_size ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'UOM','name'=>'uoms_id','type'=>'select2',
        // 					'validation'=>CRUDBooster::myAddForm()->uom ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'uoms,uom_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->uom ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Packaging','name'=>'packagings_id','type'=>'select2',
        // 					'validation'=>CRUDBooster::myAddForm()->packaging ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'packagings,packaging_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->packaging ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Vendor Type','name'=>'vendor_types_id','type'=>'select2',
        // 					'validation'=>CRUDBooster::myAddForm()->vendor_type ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'vendor_types,vendor_type_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->vendor_type ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Inventory Type','name'=>'inventory_types_id','type'=>'select2',
        // 					'validation'=>CRUDBooster::myAddForm()->inventory_type ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'inventory_types,inventory_type_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->inventory_type ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'SKU Status','name'=>'sku_statuses_id','type'=>'select2',
        // 					'validation'=>CRUDBooster::myAddForm()->sku_status ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'sku_statuses,sku_status_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->sku_status ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Tax Code','name'=>'tax_codes_id','type'=>'select2',
        // 					'validation'=>CRUDBooster::myAddForm()->tax_code ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'tax_codes,tax_code','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->tax_code ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Currency','name'=>'currencies_id','type'=>'select2',
        // 					'validation'=>CRUDBooster::myAddForm()->currency ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'currencies,currency_code','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->currency ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Purchase Price','name'=>'purchase_price','type'=>'number',
        // 					'validation'=>CRUDBooster::myAddForm()->purchase_price ? 'required' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myAddForm()->purchase_price ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'TTP','name'=>'ttp','type'=>'number',
        // 					'validation'=>CRUDBooster::myAddForm()->ttp ? 'required' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myAddForm()->ttp ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'TTP Percentage','name'=>'ttp_percentage','type'=>'number',
        // 					'validation'=>CRUDBooster::myAddForm()->ttp_percentage ? 'required' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myAddForm()->ttp_percentage ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Landed Cost','name'=>'landed_cost','type'=>'number',
        // 					'validation'=>CRUDBooster::myAddForm()->landed_cost ? 'required' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myAddForm()->landed_cost ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'MOQ Supplier','name'=>'moq_supplier','type'=>'number',
        // 					'validation'=>CRUDBooster::myAddForm()->moq ? 'required' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myAddForm()->moq ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'MOQ Store','name'=>'moq_store','type'=>'number',
        // 					'validation'=>CRUDBooster::myAddForm()->moq ? 'required' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myAddForm()->moq ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Segmentation','name'=>'segmentation','type'=>'checkbox',
        // 					'validation'=>CRUDBooster::myAddForm()->segmentation ? 'required' : '','width'=>'col-sm-6',
        // 					'datatable'=>'segmentations,segment_column_description,segment_column_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->segmentation ? : 'display:none;'];

        // 			}elseif (in_array(CRUDBooster::getCurrentMethod(), ['getEdit','postEditSave'])){

        // 				$this->form[] = ['label'=>'Tasteless Code','name'=>'tasteless_code','type'=>'text','readonly'=>true,'width'=>'col-sm-4'];

        // 				$this->form[] = ['label'=>'Company Name','name'=>'suppliers_id','type'=>'select2',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->supplier ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->supplier ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'suppliers,last_name','style'=> CRUDBooster::myEditForm()->supplier ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Trade Name','name'=>'trademarks_id','type'=>'select2',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->trademark ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->trademark ? 'integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'trademarks,trademark','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myEditForm()->trademark ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Classification','name'=>'classifications_id','type'=>'select2',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->classification ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->classification ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'classifications,classification_name','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myEditForm()->classification ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Supplier Item Code','name'=>'supplier_item_code','type'=>'text',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->supplier_item_code ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->supplier_item_code ? 'required|max:50' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myEditForm()->supplier_item_code ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Brand Description','name'=>'brands_id','type'=>'select2',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->brand_description ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->brand_description ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'brands,brand_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myEditForm()->brand_description ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'MYOB Item Description','name'=>'myob_item_description','type'=>'text',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->myob_item_description ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->myob_item_description ? 'required|min:5|max:55' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myEditForm()->myob_item_description ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Full Item Description','name'=>'full_item_description','type'=>'text',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->full_item_description ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->full_item_description ? 'required|min:5|max:255' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myEditForm()->full_item_description ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Group','name'=>'groups_id','type'=>'select2',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->group ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->group ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'groups,group_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myEditForm()->group ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Category Description','name'=>'categories_id','type'=>'select',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->category_description ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->category_description ?'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'categories,category_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myEditForm()->subcategory ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Subcategory Description','name'=>'subcategories_id','type'=>'select',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->subcategory ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->subcategory ?'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'subcategories,subcategory_description','datatable_where'=>"status=%27ACTIVE%27",'parent_select'=>'categories_id','style'=> CRUDBooster::myEditForm()->subcategory ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Type','name'=>'types_id','type'=>'select2',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->type ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->type ?'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'types,type_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myEditForm()->type ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Color Description','name'=>'colors_id','type'=>'select2',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->color_description ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->color_description ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'colors,color_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myEditForm()->color_description ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Actual Color','name'=>'actual_color','type'=>'text',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->actual_color ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->actual_color ? 'required|max:50' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myEditForm()->actual_color ? : 'display:none;'];

        // 				//$this->form[] = ['label'=>'Flavor','name'=>'flavor','type'=>'text',
        // 				//'validation'=>CRUDBooster::myEditForm()->flavor ? 'required|max:30' : '','width'=>'col-sm-4',
        // 				//'style'=> CRUDBooster::myEditForm()->flavor ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Dimension','name'=>'packaging_dimension','type'=>'number',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->packaging_dimension ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->packaging_dimension ? 'required' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myEditForm()->packaging_dimension ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Packaging Size','name'=>'packaging_size','type'=>'text',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->packaging_size ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->packaging_size ? 'required|max:30' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myEditForm()->packaging_size ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'UOM','name'=>'uoms_id','type'=>'select2',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->uom ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->uom ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'uoms,uom_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myEditForm()->uom ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Packaging','name'=>'packagings_id','type'=>'select2',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->packaging ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->packaging ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'packagings,packaging_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myEditForm()->packaging ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Vendor Type','name'=>'vendor_types_id','type'=>'select2',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->vendor_type ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->vendor_type ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'vendor_types,vendor_type_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myEditForm()->vendor_type ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Inventory Type','name'=>'inventory_types_id','type'=>'select2',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->inventory_type ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->inventory_type ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'inventory_types,inventory_type_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myEditForm()->inventory_type ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'SKU Status','name'=>'sku_statuses_id','type'=>'select2',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->sku_status ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->sku_status ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'sku_statuses,sku_status_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myEditForm()->sku_status ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Tax Code','name'=>'tax_codes_id','type'=>'select2',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->tax_code ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->tax_code ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'tax_codes,tax_code','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myEditForm()->tax_code ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Currency','name'=>'currencies_id','type'=>'select2',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->currency ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->currency ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'currencies,currency_code','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myEditForm()->currency ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Purchase Price','name'=>'purchase_price','type'=>'number',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->purchase_price ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->purchase_price ? 'required' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myEditForm()->purchase_price ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'TTP','name'=>'ttp','type'=>'number',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->ttp ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->ttp ? 'required' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myEditForm()->ttp ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'TTP Percentage','name'=>'ttp_percentage','type'=>'number',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->ttp_percentage ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->ttp_percentage ? 'required' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myEditForm()->ttp_percentage ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Landed Cost','name'=>'landed_cost','type'=>'number',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->landed_cost ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->landed_cost ? 'required' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myEditForm()->landed_cost ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'MOQ Supplier','name'=>'moq_supplier','type'=>'number',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->moq_supplier ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->moq_supplier ? 'required' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myEditForm()->moq_supplier ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'MOQ Store','name'=>'moq_store','type'=>'number',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->moq_store ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->moq_store ? 'required' : '','width'=>'col-sm-4',
        // 					'style'=> CRUDBooster::myEditForm()->moq_store ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Account Number','name'=>'chart_accounts_id','type'=>'select2',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->chart_accounts_id ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->chart_accounts_id ? 'required|integer|min:0' : '','width'=>'col-sm-4',
        // 					'datatable'=>'chart_accounts,account_number','style'=> CRUDBooster::myEditForm()->chart_accounts_id ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Segmentation','name'=>'segmentation','type'=>'checkbox-custom2',
        // 					'disabled'=>CRUDBooster::myEditReadOnly()->segmentation ? true : false,
        // 					'validation'=>CRUDBooster::myEditForm()->segmentation ? 'required' : '','width'=>'col-sm-6',
        // 					'datatable'=>'segmentations,segment_column_description,segment_column_name','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myEditForm()->segmentation ? : 'display:none;'];
        // 			}else{

        // 				$this->form[] = ['label'=>'Tasteless Code','name'=>'tasteless_code','type'=>'text','readonly'=>true,'width'=>'col-sm-4'];

        // 				$this->form[] = ['label'=>'Company Name','name'=>'suppliers_id','type'=>'select2','width'=>'col-sm-4',
        // 					'datatable'=>'suppliers,last_name'];

        // 				$this->form[] = ['label'=>'Trade Name','name'=>'trademarks_id','type'=>'select2','width'=>'col-sm-4',
        // 					'datatable'=>'trademarks,trademark','datatable_where'=>"status='ACTIVE'"];

        // 				$this->form[] = ['label'=>'Classification','name'=>'classifications_id','type'=>'select2','width'=>'col-sm-4',
        // 					'datatable'=>'classifications,classification_name','datatable_where'=>"status='ACTIVE'"];

        // 				$this->form[] = ['label'=>'Supplier Item Code','name'=>'supplier_item_code','type'=>'text','width'=>'col-sm-4'];

        // 				$this->form[] = ['label'=>'Brand Description','name'=>'brands_id','type'=>'select2','width'=>'col-sm-4',
        // 					'datatable'=>'brands,brand_description','datatable_where'=>"status='ACTIVE'"];

        // 				$this->form[] = ['label'=>'MYOB Item Description','name'=>'myob_item_description','type'=>'text','width'=>'col-sm-4'];

        // 				$this->form[] = ['label'=>'Full Item Description','name'=>'full_item_description','type'=>'text','width'=>'col-sm-4'];

        // 				$this->form[] = ['label'=>'Group','name'=>'groups_id','type'=>'select2','width'=>'col-sm-4',
        // 					'datatable'=>'groups,group_description','datatable_where'=>"status='ACTIVE'"];

        // 				$this->form[] = ['label'=>'Category Description','name'=>'categories_id','type'=>'select','width'=>'col-sm-4',
        // 					'datatable'=>'categories,category_description','datatable_where'=>"status='ACTIVE'"];

        // 				$this->form[] = ['label'=>'Subcategory Description','name'=>'subcategories_id','type'=>'select','width'=>'col-sm-4',
        // 					'datatable'=>'subcategories,subcategory_description','datatable_where'=>"status=%27ACTIVE%27",'parent_select'=>'categories_id'];

        // 				$this->form[] = ['label'=>'Type','name'=>'types_id','type'=>'select2','width'=>'col-sm-4',
        // 					'datatable'=>'types,type_description','datatable_where'=>"status='ACTIVE'"];

        // 				$this->form[] = ['label'=>'Color Description','name'=>'colors_id','type'=>'select2','width'=>'col-sm-4',
        // 					'datatable'=>'colors,color_description','datatable_where'=>"status='ACTIVE'"];

        // 				$this->form[] = ['label'=>'Actual Color','name'=>'actual_color','type'=>'text','width'=>'col-sm-4'];

        // 				$this->form[] = ['label'=>'Flavor','name'=>'flavor','type'=>'text',
        // 				'validation'=>CRUDBooster::myEditForm()->flavor ? 'required|max:50' : '','width'=>'col-sm-4',
        // 				'style'=> CRUDBooster::myEditForm()->flavor ? : 'display:none;'];

        // 				$this->form[] = ['label'=>'Dimension','name'=>'packaging_dimension','type'=>'number','width'=>'col-sm-4'];

        // 				$this->form[] = ['label'=>'Packaging Size','name'=>'packaging_size','type'=>'text','width'=>'col-sm-4'];

        // 				$this->form[] = ['label'=>'UOM','name'=>'uoms_id','type'=>'select2','width'=>'col-sm-4',
        // 					'datatable'=>'uoms,uom_description','datatable_where'=>"status='ACTIVE'"];

        // 				$this->form[] = ['label'=>'Packaging','name'=>'packagings_id','type'=>'select2','width'=>'col-sm-4',
        // 					'datatable'=>'packagings,packaging_description','datatable_where'=>"status='ACTIVE'"];

        // 				$this->form[] = ['label'=>'Vendor Type','name'=>'vendor_types_id','type'=>'select2','width'=>'col-sm-4',
        // 					'datatable'=>'vendor_types,vendor_type_description','datatable_where'=>"status='ACTIVE'"];

        // 				$this->form[] = ['label'=>'Inventory Type','name'=>'inventory_types_id','type'=>'select2','width'=>'col-sm-4',
        // 					'datatable'=>'inventory_types,inventory_type_description','datatable_where'=>"status='ACTIVE'"];

        // 				$this->form[] = ['label'=>'SKU Status','name'=>'sku_statuses_id','type'=>'select2','width'=>'col-sm-4',
        // 					'datatable'=>'sku_statuses,sku_status_description','datatable_where'=>"status='ACTIVE'"];

        // 				$this->form[] = ['label'=>'Tax Code','name'=>'tax_codes_id','type'=>'select2',
        // 					'datatable'=>'tax_codes,tax_code','datatable_where'=>"status='ACTIVE'"];

        // 				$this->form[] = ['label'=>'Currency','name'=>'currencies_id','type'=>'select2','width'=>'col-sm-4',
        // 					'datatable'=>'currencies,currency_code','datatable_where'=>"status='ACTIVE'"];

        // 				$this->form[] = ['label'=>'Purchase Price','name'=>'purchase_price','type'=>'number','width'=>'col-sm-4'];

        // 				$this->form[] = ['label'=>'TTP','name'=>'ttp','type'=>'number','width'=>'col-sm-4'];

        // 				$this->form[] = ['label'=>'TTP Percentage','name'=>'ttp_percentage','type'=>'number','width'=>'col-sm-4'];

        // 				$this->form[] = ['label'=>'Landed Cost','name'=>'landed_cost','type'=>'number','width'=>'col-sm-4'];

        // 				$this->form[] = ['label'=>'MOQ Supplier','name'=>'moq_supplier','type'=>'number','width'=>'col-sm-4'];

        // 				$this->form[] = ['label'=>'MOQ Store','name'=>'moq_store','type'=>'number','width'=>'col-sm-4'];

        // 				$this->form[] = ['label'=>'Account Number','name'=>'chart_accounts_id','type'=>'select2','datatable'=>'chart_accounts,account_number','width'=>'col-sm-4'];

        // 				$segmentation_data = DB::table('segmentations')->where('status','ACTIVE')->get();

        // 				foreach($segmentation_data as $segment){

        // 					$this->form[] = ['label'=>'+'." ".$segment->segment_column_description,'name'=>$segment->segment_column_name,'type'=>'checkbox-custom','width'=>'col-sm-4'];

        // 				}

        // 			}
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
			if(CRUDBooster::isUpdate() && (in_array(CRUDBooster::myPrivilegeName(),['Administrator','Supervisor (Purchaser)','Supervisor (Accounting)','Cost Accounting','Manager (Accounting)','Manager (Purchaser)']) || CRUDBooster::isSuperadmin()) ) {
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
			$module_id = DB::table('cms_moduls')->where('controller','AdminItemMastersController')->value('id');
			$approver_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->where('approver_privilege_id',CRUDBooster::myPrivilegeId())->where('status','ACTIVE')->first();
// 			if($approver_checker == null){
// 				$this->load_js[] = asset("js/item_master_approval.js");
// 			}
	        
	        
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
			if($button_name == 'APPROVED'){
		
				foreach ($id_selected as $key=>$value){
					
					$item_info = ItemMasterApproval::where('id',$value)->first();

					switch ($item_info->action_type){
						//create workflow
						case 'Create' :
							$module_id = DB::table('cms_moduls')->where('controller','AdminItemMastersController')->value('id');
							$approver_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->where('action_type','Create')
												->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
												->where('status','ACTIVE')->first();
							
							switch ($approver_checker->workflow_number){
							    
									case '1' :
											ItemMaster::whereIn('id',$id_selected)->update([
												'approval_status'				=> $approver_checker->next_state,
												'approved_at_1' 				=> date('Y-m-d H:i:s'), 
												'approved_by_1' 				=> CRUDBooster::myId(),
												'approver_privilege_id_1' 		=> CRUDBooster::myPrivilegeId()
											]);
											ItemMasterApproval::whereIn('id',$id_selected)->update([
												'approval_status'				=> $approver_checker->next_state,
												'approved_at_1'	 				=> date('Y-m-d H:i:s'), 
												'approved_by_1' 				=> CRUDBooster::myId(),
												'approver_privilege_id_1' 		=> CRUDBooster::myPrivilegeId()
											]);
			
											foreach ($id_selected as $key=>$value){
												//get encoder id
												$item_info = ItemMasterApproval::where('id',$value)->first();
												//send notification to encoder
												$config['content'] = CRUDBooster::myName(). " has approved your item at Item For Approval Level 1 Module!";
												$config['to'] = CRUDBooster::adminPath('item_masters/detail/'.$item_info->id);
			
												if($item_info->action_type == "Create"){
													$config['id_cms_users'] = [$item_info->created_by];
												}
												else{
													$config['id_cms_users'] = [$item_info->updated_by];
												}
			
												CRUDBooster::sendNotification($config);
			
													$approvers = ApprovalWorkflowSetting::where('status','ACTIVE')->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->where('action_type','Create')
																->where('workflow_number','2')->get();
												
													foreach ($approvers as $approvers_list){
														$approver_privilege_for =	DB::table('cms_privileges')->where('id',$approvers_list->encoder_privilege_id)->first();
														$approver_privilege =		DB::table('cms_privileges')->where('id',$approvers_list->approver_privilege_id)->first();	
													
														if($item_info->approver_privilege_id_1 == $approver_privilege_for->id){
															$send_to =	DB::table('cms_users')->where('id_cms_privileges',$approver_privilege->id)->get();
															foreach ($send_to as $send_now){
																$config['content'] = "An item has been approved at Item For Approval Level 1 Module, please check item for approval!";
																$config['to'] = CRUDBooster::adminPath('item_approval?q='.$item_info->id);
																$config['id_cms_users'] = [$send_now->id];
																CRUDBooster::sendNotification($config);	
															}
														}
														
													}
											}
			
											CRUDBooster::redirect(CRUDBooster::mainpath(""),"The item has been approved successfully !","success");
									break;
			
									case '2' :
										//check if value of ttp is not null
										
										switch ($item_info->ttp){

											case NULL:
												CRUDBooster::redirect(CRUDBooster::mainpath(""),"Item ".$item_info->full_item_description." has no TTP you can not approve this item!","warning");
											break;

											default:
												foreach ($id_selected as $key=>$value){
    											    $tasteless_code = 0;
    											    $code_column = "";
    												//get encoder id
    												$item_info = ItemMasterApproval::where('id',$value)->first();
    			
    												$group = Group::findOrFail($item_info->groups_id);
    
    												if (substr($group->group_description, 0, 4) == 'FOOD' || substr($group->group_description, 0, 4) == 'food') {
    													$tasteless_code = CodeCounter::where('type', 'ITEM MASTER')->value('code_1');
    										
    													$code_column = "code_1";
    												} elseif ($group->group_description == 'BEVERAGE' || $group->group_description == 'beverage') {
    													$tasteless_code = CodeCounter::where('type', 'ITEM MASTER')->value('code_2');
    												
    													$code_column = "code_2";
    												} elseif ($group->group_description == 'FINISHED GOODS' || $group->group_description == 'finished goods') {
    													$tasteless_code = CodeCounter::where('type', 'ITEM MASTER')->value('code_1');
    													
    													$code_column = "code_1";
    												} elseif (substr($group->group_description, -8) == 'SUPPLIES' || substr($group->group_description, -8) == 'supplies') {
    													$tasteless_code = CodeCounter::where('type', 'ITEM MASTER')->value('code_3');
    													
    													$code_column = "code_3";
    												} elseif ($group->group_description == 'CAPEX' || $group->group_description == 'capex') {
    													$tasteless_code = CodeCounter::where('type', 'ITEM MASTER')->value('code_5');
    													
    													$code_column = "code_5";
    												} elseif ($group->group_description == 'COMPLIMENTARY' || $group->group_description == 'complimentary') {
    													$tasteless_code = CodeCounter::where('type', 'ITEM MASTER')->value('code_7');
    													
    													$code_column = "code_7";
    												} elseif (substr($group->group_description, -4) == 'FEES' || substr($group->group_description, -4) == 'fees') {
    													$tasteless_code = CodeCounter::where('type', 'ITEM MASTER')->value('code_4');
    													
    													$code_column = "code_4";
    												} else {
    													$tasteless_code = CodeCounter::where('type', 'ITEM MASTER')->value('code_6');
    													
    													$code_column = "code_6";
    												}
    			
    												ItemMasterApproval::where('id',$item_info['id'])->update([
    														'tasteless_code' 				=> $tasteless_code,
    														'approval_status'				=> $approver_checker->next_state,
    														'approved_at_4'	 				=> date('Y-m-d H:i:s'), 
    														'approved_by_4' 				=> CRUDBooster::myId(),
    														'approver_privilege_id_4' 		=> CRUDBooster::myPrivilegeId()
    												]);
    												
    												$new_item_info = ItemMasterApproval::where('id',$value)->first();
    			
    												ItemMaster::where('id',$new_item_info['id'])->update([
    													'tasteless_code' 				=> $new_item_info['tasteless_code'],
    													'suppliers_id' 					=> $new_item_info['suppliers_id'],
    													'trademarks_id' 				=> $new_item_info['trademarks_id'],
    													'classifications_id' 			=> $new_item_info['classifications_id'],
    													'supplier_item_code' 			=> $new_item_info['supplier_item_code'],
    													//'myob_item_description' 		=> $new_item_info['myob_item_description'],
    													'full_item_description' 		=> $new_item_info['full_item_description'],
    													'brands_id' 					=> $new_item_info['brands_id'],
    													'groups_id' 					=> $new_item_info['groups_id'],
    													'categories_id' 				=> $new_item_info['categories_id'],
    													'subcategories_id'				=> $new_item_info['subcategories_id'],
    													'types_id'						=> $new_item_info['types_id'],
    													'colors_id'						=> $new_item_info['colors_id'],
    													'actual_color'					=> $new_item_info['actual_color'],
    													'flavor'						=> $new_item_info['flavor'],
    													'packaging_size'				=> $new_item_info['packaging_size'],
    													'packaging_dimension'					=> $new_item_info['packaging_dimension'],
    													'uoms_id' 						=> $new_item_info['uoms_id'],
    													'packagings_id' 				=> $new_item_info['packagings_id'],
    													'vendor_types_id' 				=> $new_item_info['vendor_types_id'],
    													'vendor_types_id' 				=> $new_item_info['vendor_types_id'],
    													'sku_statuses_id' 				=> $new_item_info['sku_statuses_id'],
    													'tax_codes_id' 					=> $new_item_info['tax_codes_id'],
    													'currencies_id' 				=> $new_item_info['currencies_id'],
    													'chart_accounts_id' 			=> $new_item_info['chart_accounts_id'],
    													'purchase_price' 				=> $new_item_info['purchase_price'],
    													'ttp' 							=> $new_item_info['ttp'],
    													'ttp_percentage' 				=> $new_item_info['ttp_percentage'],
    													'landed_cost' 					=> $new_item_info['landed_cost'],
    													'moq_supplier' 					=> $new_item_info['moq_supplier'],
    													'moq_store' 					=> $new_item_info['moq_store'],
    													'segmentation' 					=> $new_item_info['segmentation'],
    													'approval_status'				=> $approver_checker->next_state,
    													'approved_at_4'	 				=> date('Y-m-d H:i:s'), 
    													'approved_by_4' 				=> CRUDBooster::myId(),
    													'approver_privilege_id_4' 		=> CRUDBooster::myPrivilegeId()
    												]);
    			                                    
    			                                    DB::connection('mysql_trs')->statement('insert into items (tasteless_code, supplier_item_code, myob_item_description, full_item_description, brand_id, group_id, category_id, subcategory_id, uom_id, packaging_id, skustatus_id, currency_id, cost_price, ttp, landed_cost, created_by, updated_by) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$new_item_info['tasteless_code'], $new_item_info['supplier_itemcode'], $new_item_info['myob_item_description'], $new_item_info['full_item_description'], $new_item_info['brands_id'], $new_item_info['groups_id'], $new_item_info['categories_id'], $new_item_info['subcategories_id'], $new_item_info['uoms_id'], $new_item_info['packagings_id'], $new_item_info['sku_statuses_id'], $new_item_info['currencies_id'], $new_item_info['purchase_price'], $new_item_info['ttp'], $new_item_info['landed_cost'], $new_item_info['created_by'], $new_item_info['updated_by']]);
    			                                    
    			                                    $segmentation_datas = DB::table('segmentations')->where('status','ACTIVE')->get();
    										
    										        foreach($segmentation_datas as $segment){
    										    
    										            ItemMaster::where('id',$new_item_info['id'])->update([
    	                                                    $segment->segment_column_name => $new_item_info[$segment->segment_column_name]
    										            ]);
    										    
                                                        $sku_value = "'".$new_item_info[$segment->segment_column_name]."'";
                                                
                                                        DB::connection('mysql_trs')->statement('update items set '.$segment->segment_column_name.' = '.$sku_value.' where tasteless_code = '.$new_item_info['tasteless_code'].'');
                                                
    										        }
    										
                                                    DB::disconnect('mysql_trs');
                                                    
    													//insert in history landed cost
    													DB::table('history_landed_costs')->insert(
    														[
    															'tasteless_code' => 	$new_item_info->tasteless_code, 
    															'item_id' => 			$new_item_info->id,
    															'brand_id' => 			$new_item_info->brands_id,
    															'landed_cost' => 		$new_item_info->landed_cost,
    															'created_at' => 		date('Y-m-d H:i:s')
    														]
    													);
    													//insert in history purchase price
    													DB::table('history_purchase_prices')->insert(
    														[
    															'tasteless_code' => 	$new_item_info->tasteless_code, 
    															'item_id' => 			$new_item_info->id,
    															'brand_id' => 			$new_item_info->brands_id,
    															'purchase_price' => 	$new_item_info->purchase_price,
    															'currencies_id' => 		$new_item_info->currencies_id,
    															'created_at' => 		date('Y-m-d H:i:s')
    														]
    													);
    													//insert in history ttp
    													DB::table('history_ttps')->insert(
    														[
    															'tasteless_code' => 	$new_item_info->tasteless_code, 
    															'item_id' => 			$new_item_info->id,
    															'brand_id' => 			$new_item_info->brands_id,
    															'ttp' => 				$new_item_info->ttp,
    															'ttp_percentage' => 	$new_item_info->ttp_percentage,
    															'created_at' => 		date('Y-m-d H:i:s')
    														]
    													);
    													
    												CodeCounter::where('type', 'ITEM MASTER')->increment($code_column);
    			
    												//send notification to encoder
    												
    												$config['content'] = CRUDBooster::myName(). " has approved your item at Item For Approval Level 4 Module!";
    												$config['to'] = CRUDBooster::adminPath('item_masters/detail/'.$item_info->id);
    												
    												if($item_info->action_type == "Create"){
    													$config['id_cms_users'] = [$item_info->created_by];
    												}
    												else{
    													$config['id_cms_users'] = [$item_info->updated_by];
    												}
    											
    												CRUDBooster::sendNotification($config);
											
												}
			
												CRUDBooster::redirect(CRUDBooster::mainpath(""),"The item has been approved successfully !","success");	
											break;
										}
									break;
			
									case '3' :

										switch ($item_info->chart_accounts_id){

											case NULL:
												CRUDBooster::redirect(CRUDBooster::mainpath(""),"Item ".$item_info->full_item_description." has no account number you can not approve this item!","warning");
											break;

											default:
												ItemMaster::whereIn('id',$id_selected)->update([
													'approval_status'				=> $approver_checker->next_state,
													'approved_at_3' 				=> date('Y-m-d H:i:s'), 
													'approved_by_3' 				=> CRUDBooster::myId(),
													'approver_privilege_id_3' 		=> CRUDBooster::myPrivilegeId()
												]);
												ItemMasterApproval::whereIn('id',$id_selected)->update([
													'approval_status'				=> $approver_checker->next_state,
													'approved_at_3'	 				=> date('Y-m-d H:i:s'), 
													'approved_by_3' 				=> CRUDBooster::myId(),
													'approver_privilege_id_3' 		=> CRUDBooster::myPrivilegeId()
												]);
												
												foreach ($id_selected as $key=>$value){
												//get encoder id
													$item_info = ItemMasterApproval::where('id',$value)->first();
													//send notification to encoder
													$config['content'] = CRUDBooster::myName(). " has approved your item at Item For Approval Level 3 Module!";
													$config['to'] = CRUDBooster::adminPath('item_masters/detail/'.$item_info->id);
												
													if($item_info->action_type == "Create"){
														$config['id_cms_users'] = [$item_info->created_by];
													}
													else{
														$config['id_cms_users'] = [$item_info->updated_by];
													}
												
													CRUDBooster::sendNotification($config);
												
														$approvers = ApprovalWorkflowSetting::where('status','ACTIVE')->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->where('action_type','Create')
																	->where('workflow_number','4')->get();
												
														foreach ($approvers as $approvers_list){
															$approver_privilege_for =	DB::table('cms_privileges')->where('id',$approvers_list->encoder_privilege_id)->first();
															$approver_privilege =		DB::table('cms_privileges')->where('id',$approvers_list->approver_privilege_id)->first();	
														
															if($item_info->approver_privilege_id_3 == $approver_privilege_for->id){
																$send_to =	DB::table('cms_users')->where('id_cms_privileges',$approver_privilege->id)->get();
																foreach ($send_to as $send_now){
																	if($item_info->approved_by_4 != NULL){
																		$config['content'] = "An item has been edited at Item For Approval Level 3 Module, please check item for approval!";
																	}else{
																		$config['content'] = "An item has been approved at Item For Approval Level 3 Module, please check item for approval!";
																	}
																	$config['to'] = CRUDBooster::adminPath('item_approval?q='.$item_info->id);
																	$config['id_cms_users'] = [$send_now->id];
																	CRUDBooster::sendNotification($config);	
																}
															}
														
														}
												}
												CRUDBooster::redirect(CRUDBooster::mainpath(""),"The item has been approved successfully !","success");
											break;
										}
									break;
			
									case '4' :
											foreach ($id_selected as $key=>$value){
												//get encoder id
												$tasteless_code = 0;
    											$code_column = "";
												$item_info = ItemMasterApproval::where('id',$value)->first();
			
												$group = Group::findOrFail($item_info->groups_id);
												
												if (substr($group->group_description, 0, 4) == 'FOOD' || substr($group->group_description, 0, 4) == 'food') {
													$tasteless_code = CodeCounter::where('type', 'ITEM MASTER')->value('code_1');
													
													$code_column = "code_1";
												} elseif ($group->group_description == 'BEVERAGE' || $group->group_description == 'beverage') {
													$tasteless_code = CodeCounter::where('type', 'ITEM MASTER')->value('code_2');
													
													$code_column = "code_2";
												} elseif ($group->group_description == 'FINISHED GOODS' || $group->group_description == 'finished goods') {
													$tasteless_code = CodeCounter::where('type', 'ITEM MASTER')->value('code_1');
													
													$code_column = "code_1";
												} elseif (substr($group->group_description, -8) == 'SUPPLIES' || substr($group->group_description, -8) == 'supplies') {
													$tasteless_code = CodeCounter::where('type', 'ITEM MASTER')->value('code_3');
													
													$code_column = "code_3";
												} elseif ($group->group_description == 'CAPEX' || $group->group_description == 'capex') {
													$tasteless_code = CodeCounter::where('type', 'ITEM MASTER')->value('code_5');
													
													$code_column = "code_5";
												} elseif ($group->group_description == 'COMPLIMENTARY' || $group->group_description == 'complimentary') {
													$tasteless_code = CodeCounter::where('type', 'ITEM MASTER')->value('code_7');
													
													$code_column = "code_7";
												} elseif (substr($group->group_description, -4) == 'FEES' || substr($group->group_description, -4) == 'fees') {
													$tasteless_code = CodeCounter::where('type', 'ITEM MASTER')->value('code_4');
													
													$code_column = "code_4";
												} else {
													$tasteless_code = CodeCounter::where('type', 'ITEM MASTER')->value('code_6');
													
													$code_column = "code_6";
												}
			
												ItemMasterApproval::where('id',$item_info['id'])->update([
														'tasteless_code' 				=> $tasteless_code,
														'approval_status'				=> $approver_checker->next_state,
														'approved_at_4'	 				=> date('Y-m-d H:i:s'), 
														'approved_by_4' 				=> CRUDBooster::myId(),
														'approver_privilege_id_4' 		=> CRUDBooster::myPrivilegeId()
												]);
												
												$new_item_info = ItemMasterApproval::where('id',$value)->first();
			
												ItemMaster::where('id',$new_item_info['id'])->update([
													'tasteless_code' 				=> $new_item_info['tasteless_code'],
													'suppliers_id' 					=> $new_item_info['suppliers_id'],
													'trademarks_id' 				=> $new_item_info['trademarks_id'],
													'classifications_id' 			=> $new_item_info['classifications_id'],
													'supplier_item_code' 			=> $new_item_info['supplier_item_code'],
													//'myob_item_description' 		=> $new_item_info['myob_item_description'],
													'full_item_description' 		=> $new_item_info['full_item_description'],
													'brands_id' 					=> $new_item_info['brands_id'],
													'groups_id' 					=> $new_item_info['groups_id'],
													'categories_id' 				=> $new_item_info['categories_id'],
													'subcategories_id'				=> $new_item_info['subcategories_id'],
													'types_id'						=> $new_item_info['types_id'],
													'colors_id'						=> $new_item_info['colors_id'],
													'actual_color'					=> $new_item_info['actual_color'],
													'flavor'						=> $new_item_info['flavor'],
													'packaging_size'				=> $new_item_info['packaging_size'],
													'packaging_dimension'					=> $new_item_info['packaging_dimension'],
													'uoms_id' 						=> $new_item_info['uoms_id'],
													'packagings_id' 				=> $new_item_info['packagings_id'],
													'vendor_types_id' 				=> $new_item_info['vendor_types_id'],
													'vendor_types_id' 				=> $new_item_info['vendor_types_id'],
													'sku_statuses_id' 				=> $new_item_info['sku_statuses_id'],
													'tax_codes_id' 					=> $new_item_info['tax_codes_id'],
													'currencies_id' 				=> $new_item_info['currencies_id'],
													'chart_accounts_id' 			=> $new_item_info['chart_accounts_id'],
													'purchase_price' 				=> $new_item_info['purchase_price'],
													'ttp' 							=> $new_item_info['ttp'],
													'ttp_percentage' 				=> $new_item_info['ttp_percentage'],
													'landed_cost' 					=> $new_item_info['landed_cost'],
													'moq_supplier' 					=> $new_item_info['moq_supplier'],
													'moq_store' 					=> $new_item_info['moq_store'],
													'segmentation' 					=> $new_item_info['segmentation'],
													'approval_status'				=> $approver_checker->next_state,
													'approved_at_4'	 				=> date('Y-m-d H:i:s'), 
													'approved_by_4' 				=> CRUDBooster::myId(),
													'approver_privilege_id_4' 		=> CRUDBooster::myPrivilegeId()
												]);
			                                    
			                                    $segmentation_datas = DB::table('segmentations')->where('status','ACTIVE')->get();
										
										        foreach($segmentation_datas as $segment){
										    
										            ItemMaster::where('id',$new_item_info['id'])->update([
	                                                    $segment->segment_column_name => $new_item_info[$segment->segment_column_name]
										            ]);
										    
                                                    $sku_value = "'".$new_item_info[$segment->segment_column_name]."'";
                                            
                                                    DB::connection('mysql_trs')->statement('update items set '.$segment->segment_column_name.' = '.$sku_value.' where tasteless_code = '.$new_item_info['tasteless_code'].'');
                                            
										        }
										
                                                DB::disconnect('mysql_trs');
                                                
													//insert in history landed cost
													DB::table('history_landed_costs')->insert(
														[
															'tasteless_code' => 	$new_item_info->tasteless_code, 
															'item_id' => 			$new_item_info->id,
															'brand_id' => 			$new_item_info->brands_id,
															'landed_cost' => 		$new_item_info->landed_cost,
															'created_at' => 		date('Y-m-d H:i:s')
														]
													);
													//insert in history purchase price
													DB::table('history_purchase_prices')->insert(
														[
															'tasteless_code' => 	$new_item_info->tasteless_code, 
															'item_id' => 			$new_item_info->id,
															'brand_id' => 			$new_item_info->brands_id,
															'purchase_price' => 	$new_item_info->purchase_price,
															'currencies_id' => 		$new_item_info->currencies_id,
															'created_at' => 		date('Y-m-d H:i:s')
														]
													);
													//insert in history ttp
													DB::table('history_ttps')->insert(
														[
															'tasteless_code' => 	$new_item_info->tasteless_code, 
															'item_id' => 			$new_item_info->id,
															'brand_id' => 			$new_item_info->brands_id,
															'ttp' => 				$new_item_info->ttp,
															'ttp_percentage' => 	$new_item_info->ttp_percentage,
															'created_at' => 		date('Y-m-d H:i:s')
														]
													);
													
												CodeCounter::where('type', 'ITEM MASTER')->increment($code_column);
			
												//send notification to encoder
												
												$config['content'] = CRUDBooster::myName(). " has approved your item at Item For Approval Level 4 Module!";
												$config['to'] = CRUDBooster::adminPath('item_masters/detail/'.$item_info->id);
												
												if($item_info->action_type == "Create"){
													$config['id_cms_users'] = [$item_info->created_by];
												}
												else{
													$config['id_cms_users'] = [$item_info->updated_by];
												}
											
												CRUDBooster::sendNotification($config);
											
											}
			
											CRUDBooster::redirect(CRUDBooster::mainpath(""),"The item has been approved successfully !","success");
									break;
			
									default:
									break;
							}

						break;

						//update workflow
						case 'Update' :
							$module_id = DB::table('cms_moduls')->where('controller','AdminItemMastersController')->value('id');
							$approver_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->where('action_type', 'Update')
												->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
												->where('status','ACTIVE')->first();
							
							switch ($approver_checker->workflow_number){

								case '1' :
									ItemMasterApproval::whereIn('id',$id_selected)->update([
										'approval_status'				=> $approver_checker->next_state,
										'updated_approved_at_1'	 		=> date('Y-m-d H:i:s'), 
										'updated_approved_by_1' 		=> CRUDBooster::myId(),
										'approver_privilege_id_1' 		=> CRUDBooster::myPrivilegeId()
									]);

									foreach ($id_selected as $key=>$value){
										//get encoder id
										$item_info = ItemMasterApproval::where('id',$value)->first();
										//send notification to encoder
										$config['content'] = CRUDBooster::myName(). " has approved your item at Item For Approval Level 1 Module!";
										$config['to'] = CRUDBooster::adminPath('item_masters/detail/'.$item_info->tasteless_code);
	
										if($item_info->action_type == "Create"){
											$config['id_cms_users'] = [$item_info->created_by];
										}
										else{
											$config['id_cms_users'] = [$item_info->updated_by];
										}
	
										CRUDBooster::sendNotification($config);
	
											$approvers = ApprovalWorkflowSetting::where('status','ACTIVE')->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->where('action_type', 'Update')
														->where('workflow_number','2')->get();
										
											foreach ($approvers as $approvers_list){
												$approver_privilege_for =	DB::table('cms_privileges')->where('id',$approvers_list->encoder_privilege_id)->first();
												$approver_privilege =		DB::table('cms_privileges')->where('id',$approvers_list->approver_privilege_id)->first();	
											
												if($item_info->approver_privilege_id_1 == $approver_privilege_for->id){
													$send_to =	DB::table('cms_users')->where('id_cms_privileges',$approver_privilege->id)->get();
													foreach ($send_to as $send_now){
														$config['content'] = "An item has been approved at Item For Approval Level 1 Module, please check item for approval!";
														$config['to'] = CRUDBooster::adminPath('item_approval?q='.$item_info->tasteless_code);
														$config['id_cms_users'] = [$send_now->id];
														CRUDBooster::sendNotification($config);	
													}
												}
											}
									}
									
								break;

								case '2' :

									foreach ($id_selected as $key=>$value){
										
										ItemMasterApproval::whereIn('id',$id_selected)->update([
										    'action_type' 					=> "Update",//Create
											'approval_status'				=> $approver_checker->next_state,
											'updated_approved_at_2'	 		=> date('Y-m-d H:i:s'), 
											'updated_approved_by_2' 		=> CRUDBooster::myId(),
											'approver_privilege_id_2' 		=> CRUDBooster::myPrivilegeId()
										]);
									
										$new_item_info = ItemMasterApproval::where('id',$value)->first();

										ItemMaster::where('id',$new_item_info['id'])->update([
										    'action_type' 					=> "Update",//Create
											//'tasteless_code' 				=> $new_item_info['tasteless_code'],
											'suppliers_id' 					=> $new_item_info['suppliers_id'],
											'trademarks_id' 				=> $new_item_info['trademarks_id'],
											'classifications_id' 			=> $new_item_info['classifications_id'],
											'supplier_item_code' 			=> $new_item_info['supplier_item_code'],
											//'myob_item_description' 		=> $new_item_info['myob_item_description'],
											'full_item_description' 		=> $new_item_info['full_item_description'],
											'brands_id' 					=> $new_item_info['brands_id'],
											'groups_id' 					=> $new_item_info['groups_id'],
											'categories_id' 				=> $new_item_info['categories_id'],
											'subcategories_id'				=> $new_item_info['subcategories_id'],
											'types_id'						=> $new_item_info['types_id'],
											'colors_id'						=> $new_item_info['colors_id'],
											'actual_color'					=> $new_item_info['actual_color'],
											'flavor'						=> $new_item_info['flavor'],
											'packaging_size'				=> $new_item_info['packaging_size'],
											'packaging_dimension'					=> $new_item_info['packaging_dimension'],
											'uoms_id' 						=> $new_item_info['uoms_id'],
											'packagings_id' 				=> $new_item_info['packagings_id'],
											'vendor_types_id' 				=> $new_item_info['vendor_types_id'],
											'vendor_types_id' 				=> $new_item_info['vendor_types_id'],
											'sku_statuses_id' 				=> $new_item_info['sku_statuses_id'],
											'tax_codes_id' 					=> $new_item_info['tax_codes_id'],
											'currencies_id' 				=> $new_item_info['currencies_id'],
											'chart_accounts_id' 			=> $new_item_info['chart_accounts_id'],
											'purchase_price' 				=> $new_item_info['purchase_price'],
											'ttp' 							=> $new_item_info['ttp'],
											'ttp_percentage' 				=> $new_item_info['ttp_percentage'],
											'landed_cost' 					=> $new_item_info['landed_cost'],
											'moq_supplier' 					=> $new_item_info['moq_supplier'],
											'moq_store' 					=> $new_item_info['moq_store'],
											'segmentation' 					=> $new_item_info['segmentation'],
											'approval_status'				=> $approver_checker->next_state,
											'approved_at_4'	 				=> date('Y-m-d H:i:s'), 
											'approved_by_4' 				=> CRUDBooster::myId(),
											'approver_privilege_id_4' 		=> CRUDBooster::myPrivilegeId(),
											'updated_approved_by_1' 		=> $new_item_info['updated_approved_by_1'],
											'updated_approved_at_1' 		=> $new_item_info['updated_approved_at_1'],
											'updated_approved_by_2' 		=> $new_item_info['updated_approved_by_2'],
											'updated_approved_at_2' 		=> $new_item_info['updated_approved_at_2'],
										]);
										
										$segmentation_datas = DB::table('segmentations')->where('status','ACTIVE')->get();
										
										foreach($segmentation_datas as $segment){
										    
										    ItemMaster::where('id',$new_item_info['id'])->update([
	                                                $segment->segment_column_name => $new_item_info[$segment->segment_column_name]
										    ]);
										    
                                            $sku_value = "'".$new_item_info[$segment->segment_column_name]."'";
                                            
                                            DB::connection('mysql_trs')->statement('update items set '.$segment->segment_column_name.' = '.$sku_value.' where tasteless_code = '.$new_item_info['tasteless_code'].'');
										}
										
                                        DB::disconnect('mysql_trs');
                                        
										//update in history landed cost
										HistoryLandedCost::where('tasteless_code',$new_item_info['tasteless_code'])->update(
											[
												'tasteless_code' => 	$new_item_info->tasteless_code, 
												'item_id' => 			$new_item_info->id,
												'brand_id' => 			$new_item_info->brands_id,
												'landed_cost' => 		$new_item_info->landed_cost,
												'updated_at' => 		date('Y-m-d H:i:s'),
												'updated_by' => 		$new_item_info->updated_by,
											]
										);
										//update in history purchase price
										HistoryPurchasePrice::where('tasteless_code',$new_item_info['tasteless_code'])->update(
											[
												'tasteless_code' => 	$new_item_info->tasteless_code, 
												'item_id' => 			$new_item_info->id,
												'brand_id' => 			$new_item_info->brands_id,
												'purchase_price' => 	$new_item_info->purchase_price,
												'currencies_id' => 		$new_item_info->currencies_id,
												'updated_at' => 		date('Y-m-d H:i:s'),
												'updated_by' => 		$new_item_info->updated_by,
											]
										);
										//update in history ttp
										HistoryTtp::where('tasteless_code',$new_item_info['tasteless_code'])->update(
											[
												'tasteless_code' => 	$new_item_info->tasteless_code, 
												'item_id' => 			$new_item_info->id,
												'brand_id' => 			$new_item_info->brands_id,
												'ttp' => 				$new_item_info->ttp,
												'ttp_percentage' => 	$new_item_info->ttp_percentage,
												'updated_at' => 		date('Y-m-d H:i:s'),
												'updated_by' => 		$new_item_info->updated_by,
											]
										);

										//send notification to encoder
	
										$config['content'] = CRUDBooster::myName(). " has approved your item at Item For Approval Level 2 Module!";
										$config['to'] = CRUDBooster::adminPath('item_masters/detail/'.$item_info->tasteless_code);
											
										if($item_info->action_type == "Create"){
												$config['id_cms_users'] = [$item_info->created_by];
										}else{
												$config['id_cms_users'] = [$item_info->updated_by];
										}
											CRUDBooster::sendNotification($config);
									}

									CRUDBooster::redirect(CRUDBooster::mainpath(""),"The item has been approved successfully !","success");
								break;

								case '4' :
									foreach ($id_selected as $key=>$value){
										
										ItemMasterApproval::whereIn('id',$id_selected)->update([
										    'action_type' 					=> "Update",//Create
											'approval_status'				=> $approver_checker->next_state,
											'updated_approved_at_1'	 		=> date('Y-m-d H:i:s'), 
											'updated_approved_by_1' 		=> CRUDBooster::myId(),
											'approver_privilege_id_1' 		=> CRUDBooster::myPrivilegeId()
										]);
									
										$new_item_info = ItemMasterApproval::where('id',$value)->first();

										ItemMaster::where('id',$new_item_info['id'])->update([
										    'action_type' 					=> "Update",//Create
											//'tasteless_code' 				=> $new_item_info['tasteless_code'],
											'suppliers_id' 					=> $new_item_info['suppliers_id'],
											'trademarks_id' 				=> $new_item_info['trademarks_id'],
											'classifications_id' 			=> $new_item_info['classifications_id'],
											'supplier_item_code' 			=> $new_item_info['supplier_item_code'],
											//'myob_item_description' 		=> $new_item_info['myob_item_description'],
											'full_item_description' 		=> $new_item_info['full_item_description'],
											'brands_id' 					=> $new_item_info['brands_id'],
											'groups_id' 					=> $new_item_info['groups_id'],
											'categories_id' 				=> $new_item_info['categories_id'],
											'subcategories_id'				=> $new_item_info['subcategories_id'],
											'types_id'						=> $new_item_info['types_id'],
											'colors_id'						=> $new_item_info['colors_id'],
											'actual_color'					=> $new_item_info['actual_color'],
											'flavor'						=> $new_item_info['flavor'],
											'packaging_size'				=> $new_item_info['packaging_size'],
											'packaging_dimension'					=> $new_item_info['packaging_dimension'],
											'uoms_id' 						=> $new_item_info['uoms_id'],
											'packagings_id' 				=> $new_item_info['packagings_id'],
											'vendor_types_id' 				=> $new_item_info['vendor_types_id'],
											'vendor_types_id' 				=> $new_item_info['vendor_types_id'],
											'sku_statuses_id' 				=> $new_item_info['sku_statuses_id'],
											'tax_codes_id' 					=> $new_item_info['tax_codes_id'],
											'currencies_id' 				=> $new_item_info['currencies_id'],
											'chart_accounts_id' 			=> $new_item_info['chart_accounts_id'],
											'purchase_price' 				=> $new_item_info['purchase_price'],
											'ttp' 							=> $new_item_info['ttp'],
											'ttp_percentage' 				=> $new_item_info['ttp_percentage'],
											'landed_cost' 					=> $new_item_info['landed_cost'],
											'moq_supplier' 					=> $new_item_info['moq_supplier'],
											'moq_store' 					=> $new_item_info['moq_store'],
											'segmentation' 					=> $new_item_info['segmentation'],
											'approval_status'				=> $approver_checker->next_state,
											'approved_at_4'	 				=> date('Y-m-d H:i:s'), 
											'approved_by_4' 				=> CRUDBooster::myId(),
											'approver_privilege_id_4' 		=> CRUDBooster::myPrivilegeId(),
											'updated_approved_by_1' 		=> $new_item_info['updated_approved_by_1'],
											'updated_approved_at_1' 		=> $new_item_info['updated_approved_at_1'],
											'updated_approved_by_2' 		=> $new_item_info['updated_approved_by_2'],
											'updated_approved_at_2' 		=> $new_item_info['updated_approved_at_2'],
										]);
										
										$segmentation_datas = DB::table('segmentations')->where('status','ACTIVE')->get();
										
										foreach($segmentation_datas as $segment){
										    
										    ItemMaster::where('id',$new_item_info['id'])->update([
	                                                $segment->segment_column_name => $new_item_info[$segment->segment_column_name]
										    ]);
										    
                                            $sku_value = "'".$new_item_info[$segment->segment_column_name]."'";
                                            
                                            DB::connection('mysql_trs')->statement('update items set '.$segment->segment_column_name.' = '.$sku_value.' where tasteless_code = '.$new_item_info['tasteless_code'].'');
                                            
										}
										
                                        DB::disconnect('mysql_trs');
										
										//update in history landed cost
										HistoryLandedCost::where('tasteless_code',$new_item_info['tasteless_code'])->update(
											[
												'tasteless_code' => 	$new_item_info->tasteless_code, 
												'item_id' => 			$new_item_info->id,
												'brand_id' => 			$new_item_info->brands_id,
												'landed_cost' => 		$new_item_info->landed_cost,
												'updated_at' => 		date('Y-m-d H:i:s'),
												'updated_by' => 		$new_item_info->updated_by,
											]
										);
										//update in history purchase price
										HistoryPurchasePrice::where('tasteless_code',$new_item_info['tasteless_code'])->update(
											[
												'tasteless_code' => 	$new_item_info->tasteless_code, 
												'item_id' => 			$new_item_info->id,
												'brand_id' => 			$new_item_info->brands_id,
												'purchase_price' => 	$new_item_info->purchase_price,
												'currencies_id' => 		$new_item_info->currencies_id,
												'updated_at' => 		date('Y-m-d H:i:s'),
												'updated_by' => 		$new_item_info->updated_by,
											]
										);
										//update in history ttp
										HistoryTtp::where('tasteless_code',$new_item_info['tasteless_code'])->update(
											[
												'tasteless_code' => 	$new_item_info->tasteless_code, 
												'item_id' => 			$new_item_info->id,
												'brand_id' => 			$new_item_info->brands_id,
												'ttp' => 				$new_item_info->ttp,
												'ttp_percentage' => 	$new_item_info->ttp_percentage,
												'updated_at' => 		date('Y-m-d H:i:s'),
												'updated_by' => 		$new_item_info->updated_by,
											]
										);

										//send notification to encoder
										$config['content'] = CRUDBooster::myName(). " has approved your item at Item For Approval Level 2 Module!";
										$config['to'] = CRUDBooster::adminPath('item_masters/detail/'.$item_info->tasteless_code);
											
										if($item_info->action_type == "Create"){
												$config['id_cms_users'] = [$item_info->created_by];
										}else{
												$config['id_cms_users'] = [$item_info->updated_by];
										}
											CRUDBooster::sendNotification($config);
									}
								break;

								default:
								break;	
							}
						break;
						
						default:
						break;	
					}

			
				}

			}elseif($button_name == 'REJECT'){

				foreach ($id_selected as $key=>$id){

					$item_info = ItemMasterApproval::where('id',$id)->first();

					switch ($item_info->action_type){

						case 'Create' :
							$module_id = DB::table('cms_moduls')->where('controller','AdminItemMastersController')->value('id');
							$approver_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->where('action_type','Create')
												->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
												->where('status','ACTIVE')->first();
			
							switch ($approver_checker->workflow_number){
			
									case '1' :
			
											ItemMasterApproval::whereIn('id',$id_selected)->update([
												'approval_status'				=> 404,
												'approved_at_1' 				=> date('Y-m-d H:i:s'), 
												'approved_by_1' 				=> CRUDBooster::myId(),
												'approver_privilege_id_1' 		=> CRUDBooster::myPrivilegeId()
											]);
			
											foreach ($id_selected as $key=>$id) {
												//get encoder id
												$item_info = ItemMasterApproval::where('id',$id)->first();
							
												//send notification to encoder
												$config['content'] = CRUDBooster::myName(). " has rejected your item at Item For Approval Level 1 Module!";
												$config['to'] = CRUDBooster::adminPath('item_approval/edit/'.$item_info->id);
												
												if($item_info->action_type == "Create"){
													$config['id_cms_users'] = [$item_info->created_by];
												}
												else{
													$config['id_cms_users'] = [$item_info->updated_by];
												}
												
												CRUDBooster::sendNotification($config);
											}
			
											CRUDBooster::redirect(CRUDBooster::mainpath(""),"The item has been rejected successfully !","warning");	
									break;
			
									case '2' :
			
											ItemMasterApproval::whereIn('id',$id_selected)->update([
												'approval_status'				=> 404,
												'approved_at_2' 				=> date('Y-m-d H:i:s'), 
												'approved_by_2' 				=> CRUDBooster::myId(),
												'approver_privilege_id_2' 		=> CRUDBooster::myPrivilegeId()
											]);
			
											foreach ($id_selected as $key=>$id) {
												//get encoder id
												$item_info = ItemMasterApproval::where('id',$id)->first();
											
												//send notification to encoder
												$config['content'] = CRUDBooster::myName(). " has rejected your item at Item For Approval Level 2 Module!";
												$config['to'] = CRUDBooster::adminPath('item_approval/edit/'.$item_info->id);
			
												if($item_info->action_type == "Create"){
													$config['id_cms_users'] = [$item_info->created_by];
												}
												else{
													$config['id_cms_users'] = [$item_info->updated_by];
												}
			
												CRUDBooster::sendNotification($config);
											}
			
											CRUDBooster::redirect(CRUDBooster::mainpath(""),"The item has been rejected successfully !","warning");	
									break;
			
									case '3' :
			
											ItemMasterApproval::whereIn('id',$id_selected)->update([
												'approval_status'				=> ApprovalWorkflowSetting::where('workflow_number', 2)->where('action_type', 'Create')->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state'),
												'approved_at_3' 				=> date('Y-m-d H:i:s'), 
												'approved_by_3' 				=> CRUDBooster::myId(),
												'approver_privilege_id_3' 		=> CRUDBooster::myPrivilegeId()
											]);
			
											foreach ($id_selected as $key=>$id) {
												//get encoder id
												$item_info = ItemMasterApproval::where('id',$id)->first();
											
												//send notification to encoder
												$config['content'] = CRUDBooster::myName(). " has rejected your item at Item For Approval Level 3 Module!";
												$config['to'] = CRUDBooster::adminPath('item_approval/edit/'.$item_info->id);
			
												if($item_info->action_type == "Create"){
													$send_to =	DB::table('cms_users')->where('id_cms_privileges',$item_info->approver_privilege_id_2)->get();
													foreach ($send_to as $send_now){
														$config['id_cms_users'] = [$send_now->id];
													}
												}
												else{
													$send_to =	DB::table('cms_users')->where('id_cms_privileges',$item_info->approver_privilege_id_2)->get();
													foreach ($send_to as $send_now){
														$config['id_cms_users'] = [$send_now->id];
													}
												}
			
												CRUDBooster::sendNotification($config);
											}
			
											CRUDBooster::redirect(CRUDBooster::mainpath(""),"The item has been rejected successfully !","warning");	
									break;
			
									case '4' :
											ItemMasterApproval::whereIn('id',$id_selected)->update([
												'approval_status'				=> ApprovalWorkflowSetting::where('workflow_number', 3)->where('action_type', 'Create')->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state'),
												'approved_at_4' 				=> date('Y-m-d H:i:s'), 
												'approved_by_4' 				=> CRUDBooster::myId(),
												'approver_privilege_id_4' 		=> CRUDBooster::myPrivilegeId()
											]);
			
											foreach ($id_selected as $key=>$id) {
												//get encoder id
												$item_info = ItemMasterApproval::where('id',$id)->first();
											
												//send notification to encoder
												$config['content'] = CRUDBooster::myName(). " has rejected your item at Item For Approval Level 4 Module!";
												$config['to'] = CRUDBooster::adminPath('item_approval/edit/'.$item_info->id);
			
												if($item_info->action_type == "Create"){
													$send_to =	DB::table('cms_users')->where('id_cms_privileges',$item_info->approver_privilege_id_3)->get();
													foreach ($send_to as $send_now){
														$config['id_cms_users'] = [$send_now->id];
													}
												}
												else{
													$send_to =	DB::table('cms_users')->where('id_cms_privileges',$item_info->approver_privilege_id_3)->get();
													foreach ($send_to as $send_now){
														$config['id_cms_users'] = [$send_now->id];
													}
												}
			
												CRUDBooster::sendNotification($config);
											}
			
											CRUDBooster::redirect(CRUDBooster::mainpath(""),"The item has been rejected successfully !","warning");	
									break;
			
									default:
									break;
							
							}
						break;

						case 'Update' :
							$module_id = DB::table('cms_moduls')->where('controller','AdminItemMastersController')->value('id');
							$approver_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->where('action_type','Update')
												->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
												->where('status','ACTIVE')->first();
			
							switch ($approver_checker->workflow_number){

									case '1' :
											ItemMasterApproval::whereIn('id',$id_selected)->update([
												'approval_status'				=> 404,
												'updated_approved_at_1' 		=> date('Y-m-d H:i:s'), 
												'updated_approved_by_1' 		=> CRUDBooster::myId(),
												'approver_privilege_id_1' 		=> CRUDBooster::myPrivilegeId()
											]);
			
											foreach ($id_selected as $key=>$id) {
												//get encoder id
												$item_info = ItemMasterApproval::where('id',$id)->first();
							
												//send notification to encoder
												$config['content'] = CRUDBooster::myName(). " has rejected your item at Item For Approval Level 1 Module!";
												$config['to'] = CRUDBooster::adminPath('item_approval/edit/'.$item_info->id);
												
												if($item_info->action_type == "Create"){
													$config['id_cms_users'] = [$item_info->created_by];
												}
												else{
													$config['id_cms_users'] = [$item_info->updated_by];
												}
												
												CRUDBooster::sendNotification($config);
											}
			
											CRUDBooster::redirect(CRUDBooster::mainpath(""),"The item has been rejected successfully !","warning");	
									break;

									case '2' :
										ItemMasterApproval::whereIn('id',$id_selected)->update([
											'approval_status'				=> 404,
											'updated_approved_at_2' 		=> date('Y-m-d H:i:s'), 
											'updated_approved_by_2' 		=> CRUDBooster::myId(),
											'approver_privilege_id_2' 		=> CRUDBooster::myPrivilegeId()
										]);
		
										foreach ($id_selected as $key=>$id) {
											//get encoder id
											$item_info = ItemMasterApproval::where('id',$id)->first();
										
											//send notification to encoder
											$config['content'] = CRUDBooster::myName(). " has rejected your item at Item For Approval Level 2 Module!";
											$config['to'] = CRUDBooster::adminPath('item_approval/edit/'.$item_info->id);
		
											if($item_info->action_type == "Create"){
												$config['id_cms_users'] = [$item_info->created_by];
											}
											else{
												$config['id_cms_users'] = [$item_info->updated_by];
											}
		
											CRUDBooster::sendNotification($config);
										}
		
										CRUDBooster::redirect(CRUDBooster::mainpath(""),"The item has been rejected successfully !","warning");	
									break;	

									case '4' :
										ItemMasterApproval::whereIn('id',$id_selected)->update([
											'approval_status'				=> 404,
											'updated_approved_at_1' 		=> date('Y-m-d H:i:s'), 
											'updated_approved_by_1' 		=> CRUDBooster::myId(),
											'approver_privilege_id_2' 		=> CRUDBooster::myPrivilegeId()
										]);
		
										foreach ($id_selected as $key=>$id) {
											//get encoder id
											$item_info = ItemMasterApproval::where('id',$id)->first();
										
											//send notification to encoder
											$config['content'] = CRUDBooster::myName(). " has rejected your item at Item For Approval Level 2 Module!";
											$config['to'] = CRUDBooster::adminPath('item_approval/edit/'.$item_info->id);
		
											if($item_info->action_type == "Create"){
												$config['id_cms_users'] = [$item_info->created_by];
											}
											else{
												$config['id_cms_users'] = [$item_info->updated_by];
											}
		
											CRUDBooster::sendNotification($config);
										}
		
										CRUDBooster::redirect(CRUDBooster::mainpath(""),"The item has been rejected successfully !","warning");	
									break;	
							}
						break;

						default:
						break;	
					}

				}
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
					$module_id = DB::table('cms_moduls')->where('controller','AdminItemMastersController')->value('id');

					$create_item_status = ApprovalWorkflowSetting::where('workflow_number', 2)->where('action_type', 'Create')->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');
					$update_item_status = ApprovalWorkflowSetting::where('workflow_number', 2)->where('action_type', 'Update')->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');

					$sub_query->where('item_master_approvals.approval_status', $create_item_status);
					$sub_query->orWhere('item_master_approvals.approval_status', $update_item_status);
					$sub_query->orWhere('item_master_approvals.approval_status', 404);
				});
			}else{
				$query->where(function($sub_query){

					$module_id = DB::table('cms_moduls')->where('controller','AdminItemMastersController')->value('id');
					
					$approver_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')
										->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
										->where('status','ACTIVE')->first();

					switch($approver_checker){

						case NULL:
							$sub_query->where('item_master_approvals.approval_status',  404)->where('item_master_approvals.created_by',CRUDBooster::myId());
							$sub_query->orWhere('item_master_approvals.approval_status',404)->where('item_master_approvals.updated_by',CRUDBooster::myId());
						break;

						default:
							$approver_get = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')
								->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
								->where('status','ACTIVE')->get();

									foreach($approver_get as $approver){
										switch ($approver_checker->workflow_number){
											case '1' :
												$sub_query->where('item_master_approvals.approval_status',$approver->current_state)->where('item_master_approvals.encoder_privilege_id',$approver->encoder_privilege_id);
												$sub_query->orWhere('item_master_approvals.approval_status',$approver->current_state)->where('item_master_approvals.encoder_privilege_id',$approver->encoder_privilege_id);
											break;
				
											case '2' :
												$sub_query->where('item_master_approvals.approval_status', $approver->current_state)->where('item_master_approvals.approver_privilege_id_1',$approver->encoder_privilege_id);
												$sub_query->orWhere('item_master_approvals.approval_status', $approver->current_state)->where('item_master_approvals.approver_privilege_id_1',$approver->encoder_privilege_id);
											break;
				
											case '3' :
												$sub_query->where('item_master_approvals.approval_status',$approver->current_state)->where('item_master_approvals.approver_privilege_id_2',$approver->encoder_privilege_id);
												$sub_query->orWhere('item_master_approvals.approval_status', $approver->current_state)->where('item_master_approvals.approver_privilege_id_2',$approver->encoder_privilege_id);
												$sub_query->orWhere('item_master_approvals.approval_status',$approver->current_state)->where('item_master_approvals.encoder_privilege_id',$approver->encoder_privilege_id);
												$sub_query->orWhere('item_master_approvals.approval_status',404)->where('item_master_approvals.updated_by',CRUDBooster::myId());
											break;
											
											case '4' :
												$sub_query->where('item_master_approvals.approval_status',$approver->current_state)->where('item_master_approvals.approver_privilege_id_3',$approver->encoder_privilege_id);
												$sub_query->orWhere('item_master_approvals.approval_status',$approver->current_state)->where('item_master_approvals.approver_privilege_id_3',$approver->encoder_privilege_id);
												$sub_query->orWhere('item_master_approvals.approval_status',$approver->current_state)->where('item_master_approvals.encoder_privilege_id',$approver->encoder_privilege_id);
											break;
											
											default:
											break;
									 	}
									}
						break;
					}
					
					/*if($approver_checker != NULL ){
						switch ($approver_checker->workflow_number){

							case '1' :
								$sub_query->where('item_master_approvals.approval_status',$approver_checker->current_state)->where('item_master_approvals.encoder_privilege_id',$approver_checker->encoder_privilege_id);
								$sub_query->orWhere('item_master_approvals.approval_status',3)->where('item_master_approvals.encoder_privilege_id',$approver_checker->encoder_privilege_id);
							break;

							case '2' :
								$sub_query->where('item_master_approvals.approval_status',$approver_checker->current_state)->where('item_master_approvals.approver_privilege_id_1',$approver_checker->encoder_privilege_id);
								$sub_query->orWhere('item_master_approvals.approval_status',3)->where('item_master_approvals.approver_privilege_id_1',$approver_checker->encoder_privilege_id);
							break;

							case '3' :
								$sub_query->where('item_master_approvals.approval_status',$approver_checker->current_state)->where('item_master_approvals.approver_privilege_id_2',$approver_checker->encoder_privilege_id);
								//$sub_query->orWhere('item_master_approvals.approval_status',3)->where('item_master_approvals.approver_privilege_id_2',$approver_checker->encoder_privilege_id);
							break;
							
							case '4' :
								$sub_query->where('item_master_approvals.approval_status',$approver_checker->current_state)->where('item_master_approvals.approver_privilege_id_3',$approver_checker->encoder_privilege_id);
								//$sub_query->orWhere('item_master_approvals.approval_status',3)->where('item_master_approvals.approver_privilege_id_3',$approver_checker->encoder_privilege_id);
							break;
							
							default:
							break;
						}
	
					}else{
						$sub_query->where('item_master_approvals.approval_status',  404)->where('item_master_approvals.created_by',CRUDBooster::myId());
						$sub_query->orWhere('item_master_approvals.approval_status',404)->where('item_master_approvals.updated_by',CRUDBooster::myId());
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
			//$postdata["updated_by"]=CRUDBooster::myId();
			$created_item = ItemMasterApproval::where('id',$id)->first();

			switch($created_item->action_type){

				case 'Create' :
					$module_id = DB::table('cms_moduls')->where('controller','AdminItemMastersController')->value('id');
					$approver_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->where('action_type','Create')
										->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
										->where('status','ACTIVE')->first();
	
						switch ($approver_checker->workflow_number){
	
								case 2:
									$postdata["ttp"] 				= $postdata["ttp"];
									$postdata["ttp_percentage"] 	= $postdata["ttp_percentage"];
	
									ItemMasterApproval::where('id',$id)->update([
									'ttp' 							=> $postdata["ttp"],
									'ttp_percentage' 				=> $postdata["ttp_percentage"]
									]);
								break;
	
								case 3:
									$postdata["chart_accounts_id"] = $postdata["chart_accounts_id"];
	
									ItemMasterApproval::where('id',$id)->update([
										'chart_accounts_id' => $postdata["chart_accounts_id"]
									]);
								break;
	
								default:
								    
								    $sku_legend = 		  Input::all();
					                $segmentation_datas = DB::table('segmentations')->where('status','ACTIVE')->get();
				
					                foreach($segmentation_datas as $segment){
						                $segment_search = $sku_legend[$segment->segment_column_name];
					
						                ItemMasterApproval::where('id',$id)->update([
								            $segment->segment_column_name => $segment_search
						                ]);
					                }
					            
									$postdata['approval_status'] = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');	
									$postdata['updated_by'] = CRUDBooster::myId();
								break;
						}
				break;

				case 'Update' :
					
					$module_id = DB::table('cms_moduls')->where('controller','AdminItemMastersController')->value('id');

					$item_status = "";
					$encoder_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->where('action_type', 'Update')
										->where('encoder_privilege_id',CRUDBooster::myPrivilegeId())
										->where('status','ACTIVE')->get();

					foreach($encoder_checker as $encoder_list){

						switch ($encoder_list->workflow_number){
							case '4' :
								$item_status =	ApprovalWorkflowSetting::where('workflow_number', 4)->where('action_type', 'Update')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');
							break;
						
							default:
							    
							    $sku_legend = Input::all();
					            $segmentation_datas = DB::table('segmentations')->where('status','ACTIVE')->get();
				
					            foreach($segmentation_datas as $segment){
						            $segment_search = $sku_legend[$segment->segment_column_name];
					
						            ItemMasterApproval::where('id',$id)->update([
								        $segment->segment_column_name => $segment_search
						            ]);
					            }
					            
								$item_status = 	ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');
							break;	
						}
					}

					$postdata["approval_status"] = $item_status;

				break;	

				default:
				break;

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
			$created_item = ItemMasterApproval::where('id',$id)->first();

			switch($created_item->action_type){

				case 'Create' :
				$module_id = DB::table('cms_moduls')->where('controller','AdminItemMastersController')->value('id');
				$approver_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->where('action_type', 'Create')
									->where('approver_privilege_id',CRUDBooster::myPrivilegeId())
									->where('status','ACTIVE')->first();

					switch ($approver_checker->workflow_number){
						case 2:
						CRUDBooster::redirect(CRUDBooster::mainpath(),"The item has been edited and pending for approval.","info");
						break;

						case 3:
						CRUDBooster::redirect(CRUDBooster::mainpath(),"The item has been edited and pending for approval.","info");
						break;

						default:
						$created_item = ItemMasterApproval::where('id',$id)->first();
						$module_id = DB::table('cms_moduls')->where('controller','AdminItemMastersController')->value('id');
						$for_approval = ItemMasterApproval::where('id',$id)->first();
						$approvers = 	ApprovalWorkflowSetting::where('status','ACTIVE')->where('action_type', 'Create')
										->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->get();
					
						foreach ($approvers as $approvers_list){
							$approver_privilege_for =	DB::table('cms_privileges')->where('id',$approvers_list->encoder_privilege_id)->first();
							$approver_privilege =		DB::table('cms_privileges')->where('id',$approvers_list->approver_privilege_id)->first();	
						
							if($for_approval->encoder_privilege_id == $approver_privilege_for->id){
								
								$send_to =	DB::table('cms_users')->where('id_cms_privileges',$approver_privilege->id)->get();
								foreach ($send_to as $send_now){
									$config['content'] = "An item has been edited at Item Masterfile Module, please check item for approval!";
									$config['to'] = CRUDBooster::adminPath('item_approval?q='.$for_approval->id);
									$config['id_cms_users'] = [$send_now->id];
									CRUDBooster::sendNotification($config);	
								}
							}

						}
						CRUDBooster::redirect(CRUDBooster::mainpath(),"The item has been edited and pending for approval.","info");
						break;
					}
				break;

				case 'Update' :

					$module_id = DB::table('cms_moduls')->where('controller','AdminItemMastersController')->value('id');

					$for_approval = ItemMasterApproval::where('id',$id)->first();
					$approvers = 	ApprovalWorkflowSetting::where('status','ACTIVE')->where('action_type', 'Update')
									->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->get();
		
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
					CRUDBooster::redirect(CRUDBooster::mainpath(),"Your item has been edited and pending for approval.","info");
				break;

				default:
				break;

			}
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
		    $module_id = DB::table('cms_moduls')->where('controller','AdminItemMastersController')->value('id');
		    
			$item_info = ItemMasterApproval::find($id);
		
            if(CRUDBooster::myPrivilegeName() == 'Supervisor (Purchaser)'){
			    $create_update_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('approver_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');
			    $supplier_update_status = ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Update')->where('approver_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');
            }elseif(CRUDBooster::myPrivilegeName() == 'Manager (Accounting)'){
			    $create_update_status = ApprovalWorkflowSetting::where('workflow_number',  4)->where('action_type', 'Create')->where('approver_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');
			    $supplier_update_status = ApprovalWorkflowSetting::where('workflow_number',4)->where('action_type', 'Update')->where('approver_privilege_id', CRUDBooster::myPrivilegeId())->where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->value('current_state');                
            }else{
                $create_update_status = "";
                $supplier_update_status = "";
            }
			if ($item_info->approval_status == $create_update_status || $item_info->approval_status == $supplier_update_status) {
				CRUDBooster::redirect(CRUDBooster::mainpath(""),"You're not allowed to edit pending items for approval.","warning");
			}
            if(CRUDBooster::myPrivilegeName() == 'Manager (Purchaser)'){
                if ($item_info->action_type == "Update") {
                    
				    CRUDBooster::redirect(CRUDBooster::mainpath(""),"You're not allowed to edit pending items for approval.","warning");
			    }
            }
            
			return parent::getEdit($id);
		}
	}
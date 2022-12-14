<?php

namespace App\Exports;

use App\ItemMaster;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use CRUDBooster;
use DB;

class ItemExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function headings(): array {
        return [
            'Tasteless Code',
            'Co. Last Name',
            'Supplier Item Code',
            'Full Item Description',
            'Brand Code',
            'Brand Description',
            'Group',
            'Category Code',
            'Category Description',
            'Subcategory Description',
            'Dimension',
            'Packaging Size',
            'Fulfillment Type',
            'UOM',
            'Packaging',
            'SKU Status',
            'Supplier Cost',
            'Currency',
            'VAT Code',
            'MOQ Supplier',
            'MOQ Store',
        ];
    }

    public function map($data_menu_item): array {
        return [];
    }

    public function query() {
        $segmentations =  DB::table('segmentations')->where('status','ACTIVE')->orderBy('segment_column_description','ASC')->get();

        $items = ItemMaster::query()
        ->whereNotNull('tasteless_code')
        ->select(
            'item_masters.tasteless_code', 
            'suppliers.last_name',
            'item_masters.supplier_item_code',
            'item_masters.full_item_description',
            'brands.brand_code',
            'brands.brand_description',
            'groups.group_description',
            'categories.category_code',
            'categories.category_description',
            'subcategories.subcategory_description',
            'item_masters.packaging_dimension',
            'item_masters.packaging_size',
            'fulfillment_methods.fulfillment_method',
            'uoms.uom_code',
            'packagings.packaging_code',
            'sku_statuses.sku_status_description',
            'item_masters.purchase_price',
            'currencies.currency_code',
            'tax_codes.tax_description',
            'item_masters.moq_supplier',
            'item_masters.moq_store',
        );

        if(CRUDBooster::myColumnView()->segmentation){
            foreach($segmentations as $segment){

            }
        }

        if(!CRUDBooster::isSuperadmin()){
            $items->where('item_masters.sku_statuses_id','!=',2);
        }

        return $items;

    }
}

<?php

namespace App\Exports;

use App\ItemMaster;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use CRUDBooster;
use DB;

class POSExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function headings(): array {
        return [
            'Product ID', 
            'Product Name', 
            'Active Flag', 
            'Memo', 
            'Tax Type', 
            'Sale Flag', 
            'Unit of Measure', 
            'Standard Cost', 
            'List Price', 
            'Generic Name', 
            'Barcode 1', 
            'Barcode 2', 
            'Barcode 3', 
            'Alternate Code', 
            'Product Type', 
            'Class ID', 
            'Color Highlight', 
            'Supplier ID', 
            'Reorder Quantity', 
            'Track Expiry', 
            'Track Warranty', 
            'Warranty Duration', 
            'Duration Type', 
            'Category 1', 
            'Category 2', 
            'Category 3', 
            'Category 4', 
            'Category 5', 
            'Category 6'
        ];
    }

    public function map($data_posformat): array {
        return [
            $data_posformat->tasteless_code,
            $data_posformat->full_item_description,
            1, '', 0, 1,
            $data_posformat->uom_code,
            $data_posformat->landed_cost,
            $data_posformat->ttp,
            '',
            $data_posformat->supplier_itemcode,
            '', '', '', 0, '', '', '',
            0, 0, 1, 1, 'Years',
            $data_posformat->category_code,
            $data_posformat->subcategory_code,
            $data_posformat->brand_code,
            '', '', '',
        ];
    }

    public function query() {

        $data_pos = ItemMaster::query()->whereNull('item_masters.deleted_at')
            ->select('item_masters.tasteless_code', 
                'item_masters.supplier_item_code', 
                'item_masters.full_item_description', 
                'item_masters.ttp', 
                'item_masters.landed_cost', 
                'item_masters.purchase_price', 
                'brands.brand_code', 
                'categories.category_code', 
                'subcategories.subcategory_code', 
                'uoms.uom_code')
            ->leftJoin('brands', 'item_masters.brands_id', '=', 'brands.id')
            ->leftJoin('categories', 'item_masters.categories_id', '=', 'categories.id')
            ->leftJoin('subcategories', 'item_masters.subcategories_id', '=', 'subcategories.id')
            ->leftJoin('uoms', 'item_masters.uoms_id', '=', 'uoms.id');
	
            if(request()->has('filter_column')) {

                $filter_column = request()->filter_column;
                $data_pos->where(function($w) use ($filter_column) {
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
                            $data_pos->orderby($key,$sorting);
                            $filter_is_orderby = true;
                        }
                    }

                    if ($type=='between') {
                        if($key && $value) $data_pos->whereBetween($key,$value);
                    }

                    else {
                        continue;
                    }
                }
            }

        $data_pos->orderBy('item_masters.tasteless_code', 'asc');
        return $data_pos;

    }
}

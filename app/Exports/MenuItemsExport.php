<?php

namespace App\Exports;

use App\MenuItem;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use CRUDBooster;

class MenuItemsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function headings():array{
        return [
            'MENU CODE',
            'MENU DESCRIPTION',
            'PRODUCT TYPE',
            'TRANSACTION TYPE',
            'MENU TYPE',
            'CATEGORY',
            'PRICE',
            'ORIGINAL CONCEPT',
            'STATUS',
            'APPROVED DATE',
            'AVAILABLE CONCEPTS'
        ];
    } 

    public function map($data_menu_item): array {
        return [
            $data_menu_item->tasteless_menu_code,
            $data_menu_item->menu_item_description,
            $data_menu_item->menu_product_type_description,
            $data_menu_item->menu_transaction_type_description,
            $data_menu_item->menu_type_description,
            $data_menu_item->category_description,
            $data_menu_item->menu_selling_price,
            $data_menu_item->original_concept,
            $data_menu_item->menu_item_status,
            $data_menu_item->approved_at,
            $data_menu_item->available_concepts
        ];
    }

    public function query()
    {
        $menu_items = MenuItem::query()
        ->whereNotNull('tasteless_menu_code')
        ->leftJoin('menu_product_types','menu_items.menu_product_types_id','=','menu_product_types.id')
        ->leftJoin('menu_transaction_types','menu_items.menu_transaction_types_id','=','menu_transaction_types.id')
        ->leftJoin('menu_types','menu_items.menu_types_id','=','menu_types.id')
        ->leftJoin('menu_categories','menu_items.menu_categories_id','=','menu_categories.id')
        ->select(
            'menu_items.tasteless_menu_code',
            'menu_items.menu_item_description',
            'menu_product_types.menu_product_type_description',
            'menu_transaction_types.menu_transaction_type_description',
            'menu_types.menu_type_description',
            'menu_categories.category_description',
            'menu_items.menu_selling_price',
            'menu_items.original_concept',
            'menu_items.status as menu_item_status',
            'menu_items.approved_at',
            'menu_items.available_concepts');

        if (request()->has('filter_column')) {
            $filter_column = request()->filter_column;

            $menu_items->where(function($w) use ($filter_column) {
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
                        $menu_items->orderby($key,$sorting);
                        $filter_is_orderby = true;
                    }
                }

                if ($type=='between') {
                    if($key && $value) $menu_items->whereBetween($key,$value);
                }

                else {
                    continue;
                }
            }
        }
        return $menu_items;
    }
}

<?php

namespace App\Exports;

use App\MenuChoiceGroup;
use App\MenuItem;
use App\MenuOldCodeMaster;
use App\MenuPriceMaster;
use App\MenuSegmentation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use CRUDBooster;
use DB;

class MenuItemsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function headings():array{
        $segmentations =  MenuSegmentation::where('status','ACTIVE')->orderBy('menu_segment_column_description','ASC')->get();
		$old_item_codes = MenuOldCodeMaster::where('status','ACTIVE')->orderBy('menu_old_code_column_description','ASC')->get();
        $prices = MenuPriceMaster::where('status','ACTIVE')->orderBy('menu_price_column_description','ASC')->get();
        $group_choices = MenuChoiceGroup::where('status','ACTIVE')->orderBy('menu_choice_group_column_description','ASC')->get();

        $header = ['MENU CODE'];
			
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

        array_push($header, 'FOOD COST');
        array_push($header, 'FOOD COST PERCENTAGE');

        array_push($header,'ORIGINAL CONCEPT');
        array_push($header,'STATUS');

        foreach($segmentations as $segment){
            array_push($header,$segment->menu_segment_column_description);
        }

        return $header;
    } 

    public function map($data_menu_item): array {
        $segmentations =  MenuSegmentation::where('status','ACTIVE')->orderBy('menu_segment_column_description','ASC')->get();
        $old_item_codes = MenuOldCodeMaster::where('status','ACTIVE')->orderBy('menu_old_code_column_description','ASC')->get();
        $prices = MenuPriceMaster::where('status','ACTIVE')->orderBy('menu_price_column_description','ASC')->get();
        $group_choices = MenuChoiceGroup::where('status','ACTIVE')->orderBy('menu_choice_group_column_description','ASC')->get();
        
        $data_items = [
            $data_menu_item->tasteless_menu_code
        ];

        foreach($old_item_codes as $old_code){
            $item_code = $old_code->menu_old_code_column_name;
            array_push($data_items, $data_menu_item->$item_code);
        }

        array_push($data_items, $data_menu_item->pos_old_item_description);
        array_push($data_items, $data_menu_item->menu_item_description);
        array_push($data_items, $data_menu_item->menu_product_type_description);

        foreach($group_choices as $group_choice){
            $group = 'choices_'.$group_choice->menu_choice_group_column_name;
            $groupSku = 'choices_sku'.$group_choice->menu_choice_group_column_name;

            array_push($data_items, $data_menu_item->$group);
            array_push($data_items, $data_menu_item->$groupSku);
        }
        
        array_push($data_items, $data_menu_item->menu_type_description);
        array_push($data_items, $data_menu_item->category_description);
        array_push($data_items, $data_menu_item->subcategory_description);

        foreach($prices as $price){
            $item_price = $price->menu_price_column_name;
            array_push($data_items, $data_menu_item->$item_price);
        }

        array_push($data_items, $data_menu_item->food_cost);
        array_push($data_items, $data_menu_item->food_cost_percentage);

        array_push($data_items, $data_menu_item->original_concept);
        array_push($data_items, $data_menu_item->menu_item_status);
        
        foreach($segmentations as $segment){
            $seg = $segment->menu_segment_column_name;
            array_push($data_items, $data_menu_item->$seg);
        }

        return $data_items;
    }

    public function query()
    {
        $segmentations =  MenuSegmentation::where('status','ACTIVE')->orderBy('menu_segment_column_description','ASC')->get();
		$old_item_codes = MenuOldCodeMaster::where('status','ACTIVE')->orderBy('menu_old_code_column_description','ASC')->get();
        $prices = MenuPriceMaster::where('status','ACTIVE')->orderBy('menu_price_column_description','ASC')->get();
        $group_choices = MenuChoiceGroup::where('status','ACTIVE')->orderBy('menu_choice_group_column_description','ASC')->get();

        $menu_items = MenuItem::query()
        ->whereNotNull('tasteless_menu_code')
        ->leftJoin('menu_product_types','menu_items.menu_product_types_id','=','menu_product_types.id')
        ->leftJoin('menu_types','menu_items.menu_types_id','=','menu_types.id')
        ->leftJoin('menu_categories','menu_items.menu_categories_id','=','menu_categories.id')
        ->leftJoin('menu_subcategories','menu_items.menu_subcategories_id','=','menu_subcategories.id')
        ->leftJoin('menu_ingredients_approval','menu_items.id','=','menu_ingredients_approval.menu_items_id')
        ->select(
            
            'menu_items.tasteless_menu_code',
            'menu_items.pos_old_item_description',
            'menu_items.menu_item_description',
            'menu_product_types.menu_product_type_description',
            'menu_types.menu_type_description',
            'menu_categories.category_description',
            'menu_subcategories.subcategory_description',
            'menu_items.food_cost',
            'menu_items.food_cost_percentage',
            'menu_items.original_concept',
            'menu_items.status as menu_item_status',
            'menu_items.approved_at',
            'menu_items.available_concepts',
            'menu_ingredients_approval.accounting_approval_status',
            'menu_ingredients_approval.marketing_approval_status'
            );
            
        foreach($old_item_codes as $old_codes){
            $menu_items->addSelect('menu_items.'.$old_codes->menu_old_code_column_name);
        }

        foreach($segmentations as $segment){
            $menu_items->addSelect('menu_items.'.$segment->menu_segment_column_name);
        }

        foreach($group_choices as $choice){
            $menu_items->addSelect('menu_items.choices_'.$choice->menu_choice_group_column_name);
            $menu_items->addSelect('menu_items.choices_sku'.$choice->menu_choice_group_column_name);
        }

        foreach($prices as $price){
            $menu_items->addSelect('menu_items.'.$price->menu_price_column_name);
        }

        if (CRUDBooster::myPrivilegeName() == 'Chef') {

            $concept_access_id = DB::table('user_concept_acess')
                ->where('cms_users_id', CRUDBooster::myID())
                ->get('menu_segmentations_id')
                ->first()
                ->menu_segmentations_id;
            
            $concepts = DB::table('menu_segmentations')
                ->whereIn('id', explode(',', $concept_access_id))
                ->get('menu_segment_column_name')
                ->toArray();

            $menu_items->where(function($subQuery) use ($concepts) {
                foreach($concepts as $concept) {
                    $subQuery->orWhere('menu_items.' . $concept->menu_segment_column_name, '1');
                }
            });
        }

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

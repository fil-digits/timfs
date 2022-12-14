<?php

namespace App\Imports;

use App\MenuCategory;
use App\MenuChoiceGroup;
use App\MenuItem;
use App\MenuOldCodeMaster;
use App\MenuPriceMaster;
use App\MenuProductType;
use App\MenuSegmentation;
use App\MenuSubcategory;
use App\MenuTransactionType;
use App\MenuType;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use CRUDBooster;
use DB;

class MenuItemsImport implements ToModel, WithHeadingRow, WithChunkReading
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {   
        $code = $row["menu_code"];
        $data_array_segments = array();
        $data_array_old_codes = array();
        $data_array_choice_groups = array();
        $data_array_prices = array();

        $segmentations =  MenuSegmentation::where('status','ACTIVE')->orderBy('menu_segment_column_description','ASC')->get();
		$category = MenuCategory::firstOrCreate(['category_description' => $row["main_category"]]);
        $subcategory = MenuSubcategory::firstOrCreate(['categories_id' => $category->id,'subcategory_description' => $row["sub_category"]]);
        $product_type = MenuProductType::firstOrCreate(['menu_product_type_description' => $row["product_type"]]);
        $menu_type = MenuType::firstOrCreate(['menu_type_description' => $row["menu_type"]]);

        $old_item_codes = MenuOldCodeMaster::where('status','ACTIVE')->orderBy('menu_old_code_column_description','ASC')->get();
        $prices = MenuPriceMaster::where('status','ACTIVE')->orderBy('menu_price_column_description','ASC')->get();
        $group_choices = MenuChoiceGroup::where('status','ACTIVE')->orderBy('menu_choice_group_column_description','ASC')->get();


        if(is_null($row["menu_code"])){
            $next_id = MenuItem::select('id')->orderBy('id','DESC')->first();
            if($row["main_category"] == "PROMO"){
                $code = '6'.str_pad($next_id->id, 5, "0", STR_PAD_LEFT);
            }
            else{
                $code = '5'.str_pad($next_id->id, 5, "0", STR_PAD_LEFT);
            }
        }

        foreach($segmentations as $segment){
            $seg = strtolower(str_replace(" ", "_", $segment->menu_segment_column_description));
            $data_array_segments[$segment->menu_segment_column_name] = $row[$seg];
        }

        foreach($old_item_codes as $old_code){
            $oldCode = strtolower(str_replace(" ", "_", $old_code->menu_old_code_column_description));
            $data_array_old_codes[$old_code->menu_old_code_column_name] = $row[$oldCode];
        }

        foreach($prices as $price){
            $priceTrim1 = str_replace("-", "", $price->menu_price_column_description);
            $priceTrim2 = preg_replace('/\s+/', ' ', $priceTrim1);
            $priceDescription = strtolower(str_replace(" ", "_", $priceTrim2));
            $data_array_prices[$price->menu_price_column_name] = $row[$priceDescription];
        }

        foreach($group_choices as $group_choice){
            $group = strtolower(str_replace(" ", "_", $group_choice->menu_choice_group_column_description));
            $groupSku = strtolower(str_replace(" ", "_", $group_choice->menu_choice_group_column_description.'_sku'));
            $data_array_choice_groups['choices_'.$group_choice->menu_choice_group_column_name] = $row[$group];
            $data_array_choice_groups['choices_sku'.$group_choice->menu_choice_group_column_name] = $row[$groupSku];
        }

        $data_array_menu = [
            'action_type' => "Create",
            'menu_item_description' => $row["menu_description"],
            'pos_old_item_description' => $row["pos_old_description"],
            'menu_categories_id' => $category->id,
            'menu_subcategories_id' => $subcategory->id,
            'menu_product_types_id' => $product_type->id,
            'menu_types_id' => $menu_type->id,
            'original_concept' => $row["original_concept"],
            'status' => $row["status"],
            'approval_status' => 1,
            'created_by' => CRUDBooster::myId(),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        MenuItem::updateOrInsert(['tasteless_menu_code' => (string)$code],
            array_merge($data_array_menu,$data_array_segments,$data_array_old_codes,$data_array_prices,$data_array_choice_groups));
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}

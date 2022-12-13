<?php

namespace App\Imports;

use App\MenuCategory;
use App\MenuItem;
use App\MenuProductType;
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
        $segmentations =  DB::table('menu_segmentations')->where('status','ACTIVE')->orderBy('menu_segment_column_description','ASC')->get();
		$ingredients = DB::table('menu_ingredients')->where('status','ACTIVE')->orderBy('menu_ingredient_description','ASC')->get();
        $category = MenuCategory::firstOrCreate(['category_description' => $row["category"]]);
        $product_type = MenuProductType::firstOrCreate(['menu_product_type_description' => $row["product_type"]]);
        $transaction_type = MenuTransactionType::firstOrCreate(['menu_transaction_type_description' => $row["transaction_type"]]);
        $menu_type = MenuType::firstOrCreate(['menu_type_description' => $row["menu_type"]]);
        
        $code = '';
        $data_array_segments = array();
        $data_array_ingredients = array();

        if(is_null($row["menu_code"])){
            $next_id = DB::table('menu_items')->select('id')->orderBy('id','DESC')->first();
            if($row["category"] == "PROMO"){
                $code = '6'.str_pad($next_id->id, 5, "0", STR_PAD_LEFT);
            }
            else{
                $code = '5'.str_pad($next_id->id, 5, "0", STR_PAD_LEFT);
            }
            if(in_array($row["transaction_type"],["Delivery","DELIVERY"])){
                $code .='DL';
            }
            else{
                $code .='DT';
            }
            
            $row["menu_code"] = $code;
        }

        foreach($segmentations as $segment){
            $seg = strtolower(str_replace(" ", "_", $segment->menu_segment_column_description));
            $data_array_segments[$segment->menu_segment_column_name] = $row[$seg];
        }
        
        foreach($ingredients as $ingredient){
            $ing_code = 'ingredient_code_'.$ingredient->menu_ingredient_description;
            $ing_name = 'ingredient_name_'.$ingredient->menu_ingredient_description;
            $ing_qty = 'ingredient_qty_'.$ingredient->menu_ingredient_description;
            
            $data_array_ingredients[$ing_code]=$row[$ing_code];
            $data_array_ingredients[$ing_name]=$row[$ing_name];
            $data_array_ingredients[$ing_qty]=$row[$ing_qty];
        }

        $data_array_menu = [
            'action_type' => "Create",
            'menu_item_description' => $row["menu_description"],
            'menu_categories_id' => $category->id,
            'menu_product_types_id' => $product_type->id,
            'menu_transaction_types_id' => $transaction_type->id,
            'menu_types_id' => $menu_type->id,
            'menu_selling_price' => $row["price"],
            'original_concept' => $row["original_concept"],
            'available_concepts' => $row["available_concepts"],
            'status' => $row["status"],
            'approval_status' => 1,
            'created_by' => CRUDBooster::myId(),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        MenuItem::updateOrCreate(['tasteless_menu_code' => $row["menu_code"]],
            array_merge($data_array_menu,$data_array_segments,$data_array_ingredients));
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\ExcelTemplate;
use App\Imports\ItemPriceImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use CRUDBooster;
use DB;
use App\ItemMaster;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class ItemPriceUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_title'] = 'Upload Items Prices';
        $data['uploadRoute'] = route('uploadCostPrice');
        $data['uploadTemplate'] = route('downloadPriceTemplate');
        return view("upload.uploader", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        set_time_limit(0);
				
        $errors = array();
        $path_excel = $request->file('import_file')->store('temp');
        $path = storage_path('app').'/'.$path_excel;
        HeadingRowFormatter::default('none');
        $headings = (new HeadingRowImport)->toArray($path);
        HeadingRowFormatter::default('slug');
        $excelData = Excel::toArray(new ItemPriceImport, $path);
        
        $header = array("TASTELESS CODE","SALES PRICE","SALES PRICE EFFECTIVE DATE");

        for ($i=0; $i < sizeof($headings[0][0]); $i++) {
            if (!in_array($headings[0][0][$i], $header)) {
                $unMatch[] = $headings[0][0][$i];
            }
        }

        if(!empty($unMatch)) {
            return redirect()->back()->with(['message_type' => 'danger', 'message' => 'Failed ! Please check template headers, mismatched detected.']);
        }

        $items = array_unique(array_column($excelData[0], "tasteless_code"));
        $uploaded_items = array_column($excelData[0], "tasteless_code");
        
        if(count((array)$uploaded_items) != count((array)$items)){
            array_push($errors, 'duplicate item found!');
        }
        
        foreach ($items as $key => $value) {
            
            $itemExist = ItemMaster::where('tasteless_code',(string)$value)->first();
            
            if(is_null($itemExist)){
                array_push($errors, 'no item found!');
            }
        }

        foreach ($excelData[0] as $key => $value){
            //check if sale price is null
            if(is_null($value['sales_price'])){
                array_push($errors, 'Item code '.$value['tasteless_code'].' has blank sales price.');
            }
            //check if sales price effective date is null
            if(is_null($value['sales_price_effective_date'])){
                array_push($errors, 'Item code '.$value['tasteless_code'].' has blank sales price effective date.');
            }
            if(!Carbon::parse($value['sales_price_effective_date'])){
                array_push($errors, 'Item code '.$value['tasteless_code'].' has invalid sales price effective date.');
            }
        }

        if(!empty($errors)){
            return redirect('admin/item_masters')->with(['message_type' => 'danger', 'message' => 'Failed ! Please check '.implode(", ",$errors)]);
        }
        
        foreach ($excelData[0] as $key => $value)
        {
            $currentItemCode = ItemMaster::where('tasteless_code', (string)$value['tasteless_code'])->first();
            
            if($value['sales_price'] != 0){
                $commi_margin = ($value['sales_price'] - $currentItemCode->landed_cost)/$value['sales_price'];
            }else{
                $commi_margin = 0.00;
            }
            
            // History logs for item master
            $currentItemCodeArray = []; 
            $CheckTableColumn = Schema::getColumnListing('item_masters');
            foreach($CheckTableColumn as $keyname){   
                if(!empty($keyname)){
                    
                    if($keyname == "ttp"){
                        array_push($currentItemCodeArray, ['name' => ucwords($header[1]), 'old' => $currentItemCode->$keyname, 'new' => $value['sales_price']]);
                    }
                    elseif($keyname == "ttp_price_effective_date"){
                        array_push($currentItemCodeArray, ['name' => ucwords($header[2]), 'old' => $currentItemCode->$keyname, 'new' => $value['sales_price_effective_date']]);
                    }
                }
            }

            if(count($currentItemCodeArray) > 0){
                $DetailsOfItem = '<table class="table table-striped"><thead><tr><th>Column Name</th><th>Old Value</th><th>New Value</th></thead><tbody>';
                foreach ($currentItemCodeArray as $key => $ItemVal) {
                    $DetailsOfItem .= "<tr><td>".$ItemVal['name']."</td><td>".$ItemVal['old']."</td><td>".$ItemVal['new']."</td></tr>";
                }
                $DetailsOfItem .= '</tbody></table>';
                
                DB::table('history_item_masterfile')->insert([
                    'tasteless_code'	=>	$currentItemCode->tasteless_code,
                    'item_id'			=>	$currentItemCode->id,
                    'brand_id'			=>	$currentItemCode->brands_id,
                    'group_id'			=>	$currentItemCode->groups_id,
                    'action'			=>	"Upload (Costing)",
                    'ttp'               => $value['sales_price'],
                    'ttp_percentage'    => $commi_margin,
                    'old_ttp'           => $currentItemCode->ttp,
                    'old_ttp_percentage' => $currentItemCode->ttp_percentage,
                    'details'			=>	$DetailsOfItem,
                    'created_by'		=>	$currentItemCode->created_by,
                    'updated_by'		=>	CRUDBooster::myId()
                ]);
            }

            $trs_datas = [
                'ttp' => $value['sales_price'],
                'updated_at' => date('Y-m-d H:i:s')
            ];

            DB::connection('mysql_trs')->table('items')->where('tasteless_code', '=', (string)$value['tasteless_code'])->update($trs_datas);
        }

        Excel::import(new ItemPriceImport, $path);
        return redirect('admin/item_masters')->with(['message_type' => 'success', 'message' => 'Upload complete!']);


    }

    public function downloadPriceTemplate() 
    {
        $header = array("TASTELESS CODE","SALES PRICE","SALES PRICE EFFECTIVE DATE");
        
        $export = new ExcelTemplate([$header]);
        return Excel::download($export, 'costing-format-'.date("Ymd").'-'.date("h.i.sa").'.csv');
    }
}

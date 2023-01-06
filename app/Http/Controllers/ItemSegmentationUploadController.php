<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ExcelTemplate;
use App\Imports\ItemSegmentationImport;
use Maatwebsite\Excel\Facades\Excel;
use CRUDBooster;
use DB;
use App\ItemMaster;
use App\Segmentation;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class ItemSegmentationUploadController extends Controller
{
    protected $segments;

    public function __construct() {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
        $this->segments = Segmentation::where('status','ACTIVE')->orderBy('segment_column_description','ASC')->get();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_title'] = 'Upload SKU Legend';
        $data['uploadRoute'] = route('uploadSKULegend');
        $data['uploadTemplate'] = route('downloadSKULegendTemplate');
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
        $excelData = Excel::toArray(new ItemSegmentationImport, $path);

        $header = array("TASTELESS CODE");
        foreach($this->segments as $segment){
            array_push($header,$segment->segment_column_description);
        }

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

        $segment_cols = self::getActiveSkuLegend();
        $data_segments = array();
        $sku_datas = array();
        $skus =  DB::table('sku_legends')->where('status','ACTIVE')->orderBy('sku_legend','ASC')->get();
            
        foreach($skus as $sku){
            array_push($sku_datas, $sku->sku_legend);
        }

        foreach ($excelData[0] as $key => $value) {
                    
            if(is_null($value['tasteless_code'])){
                array_push($errors, 'Blank item code detected.');
            }
            foreach($segment_cols as $k_seg => $seg){
                if(!is_null($value[$seg])){
                    if(!in_array($value[$seg],$sku_datas)){
                        array_push($errors, 'Segment '.$value[$seg].' with tasteless code '.$value['tasteless_code'].' not found in submaster.');
                    }
                }
            }
        }

        if(!empty($errors)){
            return redirect('admin/item_masters')->with(['message_type' => 'danger', 'message' => 'Failed ! Please check '.implode(", ",$errors)]);
        }

        foreach ($excelData[0] as $key => $value){

            $oldItemDetail = ItemMaster::where('tasteless_code', (string)$value['tasteless_code'])->first();							
            $itemHistory = [];
            $collection = collect($value->keys());
            foreach($segment_cols as $k_seg => $seg){
                if($collection->search($seg, true) != false)
                {
                    if($value[$seg] != $oldItemDetail->$k_seg){
                        array_push($itemHistory, [
                            'fullname' 	=> $seg, 
                            'name' 		=> $k_seg, 
                            'old' 		=> $oldItemDetail->$k_seg, 
                            'new' 		=> $value[$seg]
                        ]);
                    }
                }
            }

            if(count($itemHistory) > 0){
                $DetailsOfItem = '<table class="table table-striped"><thead><tr><th>Name</th><th>Column Name</th><th>Old Value</th><th>New Value</th></thead><tbody>';
                foreach ($itemHistory as $key => $item) {
                    $DetailsOfItem .= "<tr><td>".$item['fullname']."</td><td>".$item['name']."</td><td>".$item['old']."</td><td>".$item['new']."</td></tr>";
                }
                $DetailsOfItem .= '</tbody></table>';

                DB::table('history_item_masterfile')->insert([
                    'tasteless_code'	=>	$oldItemDetail->tasteless_code,
                    'item_id'			=>	$oldItemDetail->id,
                    'brand_id'			=>	$oldItemDetail->brands_id,
                    'group_id'			=>	$oldItemDetail->groups_id,
                    'action'			=>	"Upload (Segmentation)",
                    'details'			=>	$DetailsOfItem,
                    'created_by'		=>	$oldItemDetail->created_by,
                    'updated_by'		=>	CRUDBooster::myId()
                ]);
            }

            foreach($segment_cols as $k_seg => $seg){
                if(!is_null($value[$seg])){
                    if(in_array($value[$seg],$sku_datas)){
                        $data_segments[$k_seg] = $value[$seg];
                    }
                        
                }
            }

            DB::connection('mysql_trs')->table('items')->where('tasteless_code', '=', (string)$value->tasteless_code)->update($data_segments);
        }

        Excel::import(new ItemSegmentationImport, $path);
        return redirect('admin/item_masters')->with(['message_type' => 'success', 'message' => 'Upload complete!']);
    }

    public function downloadSKULegendTemplate() {

        $header = array("TASTELESS CODE");
        foreach($this->segments as $segment){
            array_push($header,$segment->segment_column_description);
        }
        $export = new ExcelTemplate([$header]);
        return Excel::download($export, 'sku-legend-format-'.date("Ymd").'-'.date("h.i.sa").'.csv');
    }

    public function getActiveSkuLegend(){
        $segment_columns = array();
        foreach($this->segments as $segment){
                        
            $segment_header = $segment->segment_column_description;
            $l_header = str_replace(' ', '_', strtolower($segment_header)); 
            $a_header =  str_replace("'", "", $l_header); 
            $f_header =  str_replace('-', '_', $a_header);
            $segment_columns[$segment->segment_column_name ] = $f_header;
       }
       return $segment_columns;
    }
    
}

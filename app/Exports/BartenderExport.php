<?php

namespace App\Exports;

use App\ItemMaster;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use CRUDBooster;
use DB;

class BartenderExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function headings(): array {
        return [
            'Tasteless Code',
            'Supplier Item Code',
            'Price'
        ];
    }

    public function map($data_bartender): array {
        return [
            $data_bartender->tasteless_code,
            $data_bartender->supplier_item_code,
            $data_bartender->ttp
        ];
    }

    public function query() {

        $data_bartender = ItemMaster::query()
            ->whereNotNull('tasteless_code')
            ->select('tasteless_code', 'supplier_item_code', 'ttp');

            if(request()->has('filter_column')) {

                $filter_column = request()->filter_column;
                $data_bartender->where(function($w) use ($filter_column) {
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
                            $data_bartender->orderby($key,$sorting);
                            $filter_is_orderby = true;
                        }
                    }

                    if ($type=='between') {
                        if($key && $value) $data_bartender->whereBetween($key,$value);
                    }

                    else {
                        continue;
                    }
                }
            }
						
		return $data_bartender;
    }
}

<?php

namespace App\Exports;

use App\ItemMaster;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use CRUDBooster;
use DB;

class QBExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function headings(): array {
        return [
            'Active Status',
            'Type',
            'Item',
            'Description',
            'Sales Tax Code',
            'Account',
            'Cogs Account',
            'Asset Account',
            'Accumulated Depreciation',
            'Purchase Description',
            'Quantity On Hand',
            'U/M',
            'U/M Set',
            'Supplier Cost',
            'Preferred Vendor',
            'Tax Agency',
            'Price',
            'Reorder PT(Min)',
            'MPN',
            'Group',
            'Barcode',
            'Dimension',
            'Packaging Size',
            'Packaging UOM',
            'Tax Status',
            'Supplier Item Code'
        ];
    }

    public function map($data_qb_items): array {
        return [
            $data_qb_items->
            $data_qb_items->sku_status_description,
            $data_qb_items->type,
            $data_qb_items->tasteless_code,
            $data_qb_items->full_item_description,
            $data_qb_items->tax_description,
            $data_qb_items->accounts_group,
            $data_qb_items->cogs_accounts_group,
            $data_qb_items->asset_accounts_group,
            $data_qb_items->accumulated_depreciation,
            $data_qb_items->full_item_description,
            $data_qb_items->quantity_on_hand,
            $data_qb_items->uom_code,
            $data_qb_items->uoms_set_uom,
            $data_qb_items->purchase_price,
            $data_qb_items->last_name,
            $data_qb_items->tax_agency,
            $data_qb_items->price,
            $data_qb_items->reorder_pt,
            $data_qb_items->mpn,
            $data_qb_items->group,
            $data_qb_items->tasteless_code,
            $data_qb_items->packaging_dimension,
            $data_qb_items->packaging_size,
            $data_qb_items->packaging_description,
            $data_qb_items->tax_description,
            $data_qb_items->supplier_item_code
        ];
    }

    public function query() {

        $data_qb = ItemMaster::query()->whereNull('item_masters.deleted_at')
            ->select(
                'sku_statuses.sku_status_description',
                'item_masters.type',
                'item_masters.tasteless_code',
                'item_masters.full_item_description',
                'tax_codes.tax_description',
                'accounts.group_description as accounts_group',
                'cogs_accounts.group_description as cogs_accounts_group',
                'asset_accounts.group_description as asset_accounts_group',
                'item_masters.accumulated_depreciation',
                'item_masters.quantity_on_hand',
                'uoms.uom_code',
                'uoms_set.uom_code as uoms_set_uom',
                'item_masters.purchase_price',
                'suppliers.last_name',
                'item_masters.tax_agency',
                'item_masters.price',
                'item_masters.reorder_pt',
                'item_masters.mpn',
                'groups.group_description as group',
                'item_masters.packaging_dimension',
                'item_masters.packaging_size',
                'packagings.packaging_description',
                'item_masters.supplier_item_code')
            ->leftJoin('suppliers', 'item_masters.suppliers_id', '=', 'suppliers.id')
            ->leftJoin('accounts', 'item_masters.accounts_id', '=', 'accounts.id')
            ->leftJoin('cogs_accounts', 'item_masters.cogs_accounts_id', '=', 'cogs_accounts.id')
            ->leftJoin('asset_accounts', 'item_masters.asset_accounts_id', '=', 'asset_accounts.id')
            ->leftJoin('groups', 'item_masters.groups_id', '=', 'groups.id')
            ->leftJoin('sku_statuses', 'item_masters.sku_statuses_id', '=', 'sku_statuses.id')
            ->leftJoin('packagings', 'item_masters.packagings_id', '=', 'packagings.id')
            ->leftJoin('uoms', 'item_masters.uoms_id', '=', 'uoms.id')
            ->leftJoin('uoms_set', 'item_masters.uoms_set_id', '=', 'uoms_set.id')
            ->leftJoin('tax_codes', 'item_masters.tax_codes_id', '=', 'tax_codes.id');
	
            if(request()->has('filter_column')) {

                $filter_column = request()->filter_column;
                $data_qb->where(function($w) use ($filter_column) {
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
                            $data_qb->orderby($key,$sorting);
                            $filter_is_orderby = true;
                        }
                    }

                    if ($type=='between') {
                        if($key && $value) $data_qb->whereBetween($key,$value);
                    }

                    else {
                        continue;
                    }
                }
            }

        if(!CRUDBooster::isSuperadmin()){
            $data_qb->where('item_masters.sku_statuses_id', '!=',2);
        }

        $data_qb->orderBy('item_masters.tasteless_code', 'asc');
        return $data_qb;
    }
}

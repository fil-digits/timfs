<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use CRUDBooster;

class MenuItem extends Model
{
    protected $table = 'menu_items';

    protected $fillable = [
        'action_type',
        'menu_item_description',
        'menu_categories_id',
        'menu_product_types_id',
        'menu_transaction_types_id',
        'menu_types_id',
        'menu_selling_price',
        'original_concept',
        'available_concepts',
        'status',
        'approval_status',
        'created_by'
    ];

    public static function boot()
    {
       parent::boot();
       static::creating(function($model)
       {
           $model->created_by = CRUDBooster::myId();
       });
       static::updating(function($model)
       {
           $model->updated_by = CRUDBooster::myId();
       });
   }
}

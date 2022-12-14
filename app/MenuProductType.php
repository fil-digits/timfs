<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use CRUDBooster;

class MenuProductType extends Model
{
    protected $table = 'menu_product_types';

    protected $fillable = [
        'menu_product_type_description',
        'status',
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

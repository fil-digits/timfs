<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use CRUDBooster;

class ItemMaster extends Model
{
    protected $table = 'item_masters';

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

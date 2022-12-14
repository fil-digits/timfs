<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use CRUDBooster;

class MenuSubcategory extends Model
{

    protected $table = 'menu_subcategories';

    protected $fillable = [
        'categories_id',
        'subcategory_code',
        'subcategory_description',
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

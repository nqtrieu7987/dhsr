<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewType extends Model
{
    public $table = 'view_type';
    public $fillable = [
    	'id','name','created_at','updated_at','image_active','image_deactive','is_active'
    ];
}

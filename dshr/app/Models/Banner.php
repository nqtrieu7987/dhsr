<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    public $table = 'banner';
    public $fillable = [
    	'id','name','url','is_active','type','logo', 'header','footer','bg', 'created_at','updated_at'
    ];
}

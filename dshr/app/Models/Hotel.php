<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    public $table = 'hotel';
    public $fillable = [
    	'id','name','is_active','phone','address','image','logo','created_at','updated_at'
    ];
}

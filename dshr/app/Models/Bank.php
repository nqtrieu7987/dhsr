<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    public $table = 'bank';
    public $fillable = [
    	'id','name','is_active','logo','created_at','updated_at'
    ];
}

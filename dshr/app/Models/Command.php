<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Command extends Model
{
    public $table = 'command';
    public $fillable = [
    	'id','name','is_active','viettel', 'mobiphone', 'vinaphone', 'other', 'created_at','updated_at'
    ];
}

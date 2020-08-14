<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    public $table = 'phone';
    public $fillable = [
    	'id','phone','url', 'created_at','updated_at'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    public $table = 'job_type';
    public $fillable = [
    	'id','name','comment','is_active','created_at','updated_at'
    ];
}

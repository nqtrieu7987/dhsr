<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clocking extends Model
{
    public $table = 'clockings';
    public $fillable = [
    	'id','user_id','job_id','type','path','date','created_at','updated_at'
    ];

    public function users() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function jobs() {
        return $this->hasOne('App\Models\Job', 'id', 'job_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clocking extends Model
{
    public $table = 'clockings';
    public $fillable = [
    	'id','user_id','job_id','type','path','date','created_at','updated_at'
    ];

    public function Users() {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->first();
    }

    public function Jobs() {
        return $this->hasOne('App\Models\Job', 'id', 'job_id')->first();
    }
}

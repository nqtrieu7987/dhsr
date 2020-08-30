<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllJob extends Model
{
    public $table = 'all_jobs';
    public $fillable = [
    	'id','job_id','user_id','status','workTime_confirmed','real_start','real_end','timestamp','attendenceStatus','attendenceTimeStamp','paidTimeIn','paidTimeOut','breakTime','totalHours','remarks','rwsConfirmed','created_at','updated_at'
    ];

    public function Users() {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->first();
    }

    public function Jobs() {
        return $this->hasOne('App\Models\Job', 'id', 'job_id')->first();
    }
}

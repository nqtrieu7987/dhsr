<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    public $table = 'old_jobs';
    public $fillable = [
    	'id','hotel_id','job_type_id','is_active','slot','current_slot','start_time','end_time','start_date','view_type','created_at','updated_at'
    ];

    public function Types() {
        return $this->hasOne('App\Models\JobType', 'id', 'job_type_id')->first();
    }

    public function Hotels() {
        return $this->hasOne('App\Models\Hotel', 'id', 'hotel_id')->first();
    }
}

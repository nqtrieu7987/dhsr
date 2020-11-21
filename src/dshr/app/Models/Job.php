<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    public $table = 'job';
    public $fillable = [
    	'id','hotel_id','job_type_id','is_active','slot','current_slot','start_time','end_time','start_date','view_type','created_at','updated_at'
    ];

    public function scopeActive($query){
        return $query->where('is_active', 1);
    }

    public function types() {
        return $this->hasOne('App\Models\JobType', 'id', 'job_type_id');
    }

    public function hotels() {
        return $this->hasOne('App\Models\Hotel', 'id', 'hotel_id');
    }
}

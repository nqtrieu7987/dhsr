<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public $table = 'page';
    public $fillable = [
    	'id','name','is_active','command_id','code','created_at','updated_at','style','url','banner_id','article_id','article_list','button','color'
    ];

    public function Commands() {
        return $this->hasOne('App\Models\Command', 'id', 'command_id')->first();
    }

    public function Articles() {
        return $this->hasOne('App\Models\Article', 'id', 'article_id')->first();
    }

    public function Banners() {
        return $this->belongsTo('App\Models\Banner', 'banner_id', 'id')->first();
    }
}
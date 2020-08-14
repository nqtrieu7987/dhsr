<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public $table = 'article';
    public $fillable = [
    	'id','title','header','image', 'content','url', 'type','published_time','created_at','updated_at','slug','is_hot','is_active','page_id'
    ];
}
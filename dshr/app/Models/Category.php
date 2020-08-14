<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'category';

    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'name',
        'parent_id',
        'is_active',
        'slug',
    ];

    public function products() {
        return $this->hasMany('App\Models\Article', 'category_id', 'id');
    }

    public static function getCateBySlug($slug)
    {
        $query = Category::where('is_active', '=', 1)
            ->where('slug', $slug)
            ->first();
        return $query;
    }

    public static function getListCategory($limit)
    {
        $query = Category::where('is_active', '=', 1)
            ->limit($limit)
            ->get();
        return $query;
    }

}

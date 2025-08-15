<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Item extends Model
{
    public function colors()
    {
        return $this->belongsToMany(\App\Models\Color::class, 'item_colors');
    }

    protected $fillable = [
        'user_id', 'brand', 'category', 'season', 'price', 'purchased_at',
        'status', 'memo', 'wear_count', 'image', 'deleted_flg',
    ];

    protected $casts = [
        'purchased_at' => 'date',
        'deleted_flg'  => 'boolean',
        'wear_count'   => 'integer',
    ];

    

    public function categories()
    {
        return $this->belongsToMany(\App\Models\Category::class, 'item_categories');
    }
}

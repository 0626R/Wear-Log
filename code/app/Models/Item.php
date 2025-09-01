<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Color;
use App\Models\Category;
use App\Models\Season;


class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'brand', 'category', 'season', 'price', 'purchased_at',
        'status', 'memo', 'wear_count', 'image', 'deleted_flg',
    ];

    protected $casts = [
        'purchased_at' => 'date',
        'deleted_flg'  => 'boolean',
        'wear_count'   => 'integer',
    ];

    // pivot: item_colors (item_id, color_id)
    public function colors()
    {
        return $this->belongsToMany(\App\Models\Color::class, 'item_colors');
    }

    // pivot: item_categories (item_id, category_id)
    public function categories()
    {
        return $this->belongsToMany(\App\Models\Category::class, 'item_categories');
    }

}

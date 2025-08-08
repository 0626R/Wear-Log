<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Item extends Model
{
    public function colors()
    {
        return $this->belongsToMany(Color::class, 'item_colors');
    }
}

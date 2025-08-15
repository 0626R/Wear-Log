<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;   // ← 追加
    protected $fillable = ['name'];
}

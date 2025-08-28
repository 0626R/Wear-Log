<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutfitLog extends Model
{
    protected $fillable = ['user_id','date','image_path'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrapeJsImages extends Model
{
    use HasFactory;
    protected $table = 'grape_js_images';
    protected $fillable = ['id', 'image_name','image_width','image_height'];
}

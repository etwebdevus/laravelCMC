<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageContents extends Model
{
    use HasFactory;
    protected $table = 'page_contents';
    protected $fillable = ['id', 'page_id', 'page_translation_connect', 'data'];
}

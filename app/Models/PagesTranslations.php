<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagesTranslations extends Model
{
    use HasFactory;
    protected $table = 'pages_translations';
    protected $fillable = ['id', 'title', 'link', 'meta_keywords', 'meta_description', 'notes', 'cloned', 'status', 'page_id', 'locale', 'connect_same'];
    
    public function page()
    {
        return $this->belongsTo(Pages::class, 'page_id');
    }
    public function language()
    {
        return $this->belongsTo(Languages::class, 'locale');
    }
}

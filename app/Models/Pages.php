<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    use HasFactory;
    protected $table = 'pages';
    protected $fillable = ['id','title','layout','data'];
    
    public function layout(){
        return $this->belongsTo(Layouts::class, 'layout');
    }
    public function content()
    {
        return $this->belongsTo(PageContents::class, 'id','page_id');
    }
}

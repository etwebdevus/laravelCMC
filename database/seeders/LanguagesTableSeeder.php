<?php

namespace Database\Seeders;

use App\Models\Languages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Language;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Languages::truncate();
        Languages::create([
            'name' =>'English',
            'code' =>'en' 
        ]);
        Languages::create([
            'name' =>'French',
            'code' =>'fr' 
        ]);
       
    }
}

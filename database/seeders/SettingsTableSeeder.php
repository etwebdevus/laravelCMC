<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::truncate();
        Settings::create([
            'setting' =>'language',
            'value'=> 1
        ]);
        Settings::create([
            'setting' =>'page',
            'value'=>0
        ]);
    }
}

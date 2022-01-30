<?php

namespace Database\Seeders;

use App\Models\ConversionRate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConversionRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConversionRate::truncate();
        $path = app_path('../database/seeders/conv_rate.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}

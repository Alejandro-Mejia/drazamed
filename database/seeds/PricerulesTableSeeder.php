<?php

use Illuminate\Database\Seeder;

class PricerulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pricerules')->truncate();
        $path = base_path() . '/database/seeds/pricerules.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}

<?php

use Illuminate\Database\Seeder;

class ProdrulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prodrules')->truncate();
        $path = base_path() . '/database/seeds/prodrules.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}

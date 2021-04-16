<?php

use Illuminate\Database\Seeder;

class PresRulesProdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pres_rules_prod')->truncate();
        $path = base_path() . '/database/seeds/pres_rules_prod.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}

<?php

use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('favorite')->truncate();
        $path = base_path() . '/database/seeds/favorites.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}

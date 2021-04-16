<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('users')->insert(
            array(
                array('email' => 'alejomejia1@gmail.com', 'password' => '$2y$10$mDgkVUsPJK2SvdkBXWlB6uJwRFfupK/Qf7JI8lHzqfmtre/EAnZ0y', 'phone' => null, 'user_type_id' => 1, 'security_code' => null, 'user_status' => 2, 'created_by' => 1,  'updated_by' => null, 'user_id' => 1, 'remember_token' => null,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'), 'name' => 'Alejandro Mejia', 'email_verified_at' => null)
            ));
    }
}

<?php
    use Illuminate\Database\Seeder;

    class SettingSeeder extends Seeder
    {


        public function run()
        {
            $array_values = array(
                array('group' => 'site', 'key' => 'app_name', 'value' => 'DRAZAMED', 'type' => 'TEXT'),
                array('group' => 'site', 'key' => 'logo', 'value' => '/assets/img/logo.png', 'type' => 'IMAGE'),
                array('group' => 'site', 'key' => 'mail', 'value' => 'info@drazamed.com', 'type' => 'TEXT'),
                array('group' => 'site', 'key' => 'website', 'value' => 'drazamed.com', 'type' => 'TEXT'),
                array('group' => 'site', 'key' => 'address', 'value' => 'Carrera 6 No. 1-20 Cajicá - Cundinamarca', 'type' => 'TEXT'),
                array('group' => 'site', 'key' => 'timezone', 'value' => 'America/Bogota', 'type' => 'TEXT'),
                array('group' => 'site', 'key' => 'phone', 'value' => '1', 'type' => 'TEXT'),
                array('group' => 'site', 'key' => 'discount', 'value' => '0', 'type' => 'FLOAT'),
                array('group' => 'site', 'key' => 'currency', 'value' => '$', 'type' => 'TEXT'),
                array('group' => 'site', 'key' => 'curr_position', 'value' => 'BEFORE', 'type' => 'TEXT'),
                array('group' => 'mail', 'key' => 'username', 'value' => 'AKIATCMG66637CWVMJPH', 'type' => 'TEXT'),
                array('group' => 'mail', 'key' => 'password', 'value' => 'BCV0B32dlwclfRaryJ+2gSUJ+356u72C40qm9JxHuVMC', 'type' => 'TEXT'),
                array('group' => 'mail', 'key' => 'address', 'value' => 'gerencia@drazamed.com', 'type' => 'TEXT'),
                array('group' => 'mail', 'key' => 'name', 'value' => 'Juan Pablo Pedraza', 'type' => 'TEXT'),
                array('group' => 'mail', 'key' => 'port', 'value' => '587', 'type' => 'TEXT'),
                array('group' => 'mail', 'key' => 'host', 'value' => 'email-smtp.us-east-1.amazonaws.com', 'type' => 'TEXT'),
                array('group' => 'mail', 'key' => 'driver', 'value' => 'smtp', 'type' => 'TEXT'),
                array('group' => 'payment', 'key' => 'mode', 'value' => '2', 'type' => 'TEXT'),
                array('group' => 'payment', 'key' => 'type', 'value' => 'TEST', 'type' => 'TEXT')
            );
            // Find and Update
            foreach ($array_values as $value) {
                $count = DB::table('settings')->where('group', '=', $value['group'])->where('key', '=', $value['key'])->count();
                if ($count == 0) {
                    DB::table('settings')->insert(array(array('group' => $value['group'], 'key' => $value['key'], 'value' => $value['value'], 'type' => $value['type'])));
                }
            }
        }
    }


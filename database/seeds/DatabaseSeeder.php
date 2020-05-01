<?php
	use Illuminate\Database\Seeder;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Support\Str;

    class DatabaseSeeder extends Seeder
    {

        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            $this->call('UserTypeTableSeeder');
            $this->call('SettingSeeder');
            $this->call('AdminTypeSeeder');
            $this->call('InvoiceStatusSeeder');
            $this->call('ShippingStatusSeeder');
            $this->call('PaymentStatusSeeder');
            $this->call('PrescriptionStatusSeeder');
            $this->call('UserStatusSeeder');
            $this->call('PaymentGatewaySeeder');
            $this->command->info('Data table seeded!');
        }


    }

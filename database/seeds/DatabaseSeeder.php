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
            $this->call('UsersTableSeeder');
            $this->call('PaymentGatewaySeeder');
            $this->call('PresRulesCatsTableSeeder');
            $this->call('PresRulesProdSeeder');
            $this->call('PricerulesTableSeeder');
            $this->call('ProdrulesTableSeeder');
            $this->call('FavoriteSeeder');
            $this->command->info('Data table seeded!');
        }


    }

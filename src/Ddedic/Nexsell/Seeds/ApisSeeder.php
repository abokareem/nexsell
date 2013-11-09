<?php namespace Ddedic\Nexsell\Seeds;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApisSeeder extends Seeder {

    /**
     * Run the database seeding.
     *
     * @return void
     */
    public function run() {

        $apis = array(
            array(
                'api_key'           => '12345',
                'api_secret'        => '54321',
                'minute_limit'      => '30',
                'hour_limit'        => '1000',
                'plan_id'           => 1,
                'gateway_id'        => 1,
                'credit_balance'    => 10.00000,
                'active'            => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ),

            array(
                'api_key'           => '56789',
                'api_secret'        => '98765',
                'minute_limit'      => '20',
                'hour_limit'        => '500',
                'plan_id'           => 1,
                'gateway_id'        => 1,
                'credit_balance'    => 0.00000,
                'active'            => 0,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            )
        );
        DB::table('apis')->insert($apis);
        $this->command->info('Apis Table Seeded');



    }
}
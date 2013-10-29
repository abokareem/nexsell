<?php namespace Ddedic\Nexsell\Seeds;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GatewaysSeeder extends Seeder {

    /**
     * Run the database seeding.
     *
     * @return void
     */
    public function run() {

        $gateways = array(
            array(
                'name'              => 'Nexmo (default)',
                'description'       => 'Nexmo defult gateway',
                'class_name'        => 'NexmoGateway',
                'api_key'           => '--apikey--',
                'api_secret'        => '--apisecret--',
                'active'            =>  1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s')
            )
        );
        DB::table('gateways')->insert($gateways);
        $this->command->info('Gateways Table Seeded');



    }
}
<?php namespace Ddedic\Nexsell\Seeds;



use DateTime;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SentryUserSeeder extends Seeder {

    /**
     * Run the database seeding.
     *
     * @return void
     */
    public function run() {
        DB::table('clients')->delete();

        $user = array(
            'api_key'           => 'CMS',
            'api_secret'        => 'Admin',
            'minute_limit'      => 'admin@dsmg.co.uk',
            'hour_limit'        => 'password',
            'plan_id'           => 1,
            'credit_balance'    => new DateTime
        );
        Sentry::getUserProvider()->create($user);


    }
}
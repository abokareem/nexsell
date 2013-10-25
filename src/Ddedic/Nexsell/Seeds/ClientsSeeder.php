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
            'first_name'   => 'CMS',
            'last_name'    => 'Admin',
            'email'        => 'admin@dsmg.co.uk',
            'password'     => 'password',
            'activated'    => 1,
            'activated_at' => new DateTime
        );
        Sentry::getUserProvider()->create($user);


    }
}
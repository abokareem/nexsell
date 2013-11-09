<?php namespace Ddedic\Nexsell\Seeds;


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model as Eloquent;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeding.
     *
     * @return void
     */
    public function run() {
        Eloquent::unguard();

        $this->call('Ddedic\Nexsell\Seeds\ApisSeeder');
        $this->call('Ddedic\Nexsell\Seeds\PlansSeeder');
        $this->call('Ddedic\Nexsell\Seeds\GatewaysSeeder');
    }
}
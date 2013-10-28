<?php namespace Ddedic\Nexsell\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class ReinstallCommand extends Command {

        /**
         * The console command name.
         *
         * @var string
         */
        protected $name = 'nexsell:reinstall';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Delete data, and reinstall Nexsell.';

        /**
         * Execute the console command.
         *
         * @return void
         */
        public function fire()
        {
                $this->comment('Reseting Nexsell...');
                $this->call('migrate:reset');
                $this->comment('Done.');
                $this->call('nexsell:install');
        }

}
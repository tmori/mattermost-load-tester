<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Gnello\Mattermost\Driver;
use \App\Libs\MattermostUtils;

class MattermostLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mattermost:login {login_id} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'login & logout';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            MattermostUtils::initialize();
            /*
             * Login
             */
            $driver = MattermostUtils::createDriver(
                $this->argument('login_id'),
                $this->argument('password'),
                false
            );
            MattermostUtils::login($driver);
            /*
             * Logout
             */
            MattermostUtils::logout($driver);
        } catch (Exception  $e) {
            echo $e->getMessage().PHP_EOL;
        }
        
        return 0;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Gnello\Mattermost\Driver;
use \App\Libs\MattermostUtils;
use \Exception;

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
            echo "login_id=" . $this->argument('login_id') . "\n";
            MattermostUtils::login($driver);
            /*
             * Logout
             */
            MattermostUtils::logout($driver);
        } catch (\Exception  $e) {
            echo $e->getMessage().PHP_EOL;
            return 1;
        }
        
        return 0;
    }
}

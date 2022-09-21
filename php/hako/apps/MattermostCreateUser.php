<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Gnello\Mattermost\Driver;
use \App\Libs\MattermostUtils;

class MattermostCreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mattermost:create_user {username} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        try {
            MattermostUtils::initialize();
            if (MattermostUtils::setAdminUser() != 0) {
                return 1;
            }
            $driver = MattermostUtils::createDriver(
                MattermostUtils::$root_user,
                MattermostUtils::$root_passwd,
                false
            );
            /*
             * Login
             */
            MattermostUtils::login($driver);

            /*
             * Create User
             */
            echo "##START: CREATE USER\n";
            $result = $driver->getUserModel()->createUser([
                'email'    => $this->argument('username') . '@test.com', 
                'username' => $this->argument('username'), 
                'password' => $this->argument('password')
            ]);

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

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Gnello\Mattermost\Driver;

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
        $root_user = getenv('MATTERMOST_ROOT_USER');
        $root_passwd = getenv('MATTERMOST_ROOT_PASSWD');
        if ($root_user == null) {
            echo "ERROR: MATTERMOST_ROOT_USER is not set\n";
            return 1;
        }
        if ($root_passwd == null) {
            echo "ERROR: MATTERMOST_ROOT_PASSWD is not set\n";
            return 1;
        }
        echo "root_passwd=".$root_passwd;
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ALL);
        $container = new \Pimple\Container([
            'driver' => [
                'url' => getenv('MATTERMOST_URL'),
                'login_id' => $root_user,
                'password' => $root_passwd,
            ],
            'guzzle' => [
                'verify' => false
            ]
        ]);
         
        try {
            /*
             * Login
             */
            echo "##START: LOGIN\n";
            $driver = new Driver($container);
            $result = $driver->authenticate();    
            $code = strval($result->getStatusCode());
            $phrase = $result->getReasonPhrase();
            echo "code=${code} : ${phrase}\n";

            /*
             * Create User
             */
            echo "##START: CREATE USER\n";
            $result = $driver->getUserModel()->createUser([
                'email'    => $this->argument('username') . '@test.com', 
                'username' => $this->argument('username'), 
                'password' => $this->argument('password')
            ]);
            echo "code=${code} : ${phrase}\n";

            /*
             * Logout
             */
            echo "##LOGOUT\n";
            $result = $driver->getUserModel()->logoutOfUserAccount();
            $code = strval($result->getStatusCode());
            $phrase = $result->getReasonPhrase();
            echo "code=${code} : ${phrase}\n";
            
        } catch (Exception  $e) {
            echo $e->getMessage().PHP_EOL;
        }
        
        return 0;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Gnello\Mattermost\Driver;

class MattermostCreateChannel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mattermost:create_channel {team_name} {channel_name} {display_name} {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a channel';

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

            $team_name = $this->argument('team_name');
            $channel_name = $this->argument('channel_name');
            $display_name = $this->argument('display_name');
            $type = $this->argument('type');
            /*
             * Get TeamId
             */
            echo "##START: TeamId=" . $team_name . "\n";
            $result = $driver->getTeamModel()->getTeamByName($team_name);
            echo "get team rcode=${code} : ${phrase}\n";
            $res = $result->getBody();
            $jsonstr =  json_decode($res, true);
            $team_id = $jsonstr['id'];
            echo "team_id=" . $team_id . "\n";

            /*
             * Create Channel
             */
            echo "##START: CREATE CHANNEL\n";
            $result = $driver->getChannelModel()->createChannel([
                'team_id'    => $team_id, 
                'name'    => $channel_name, 
                'display_name' => $display_name, 
                'type' => $type
            ]);
            echo "create channel rcode=${code} : ${phrase}\n";
            echo "body=" . $result->getBody() . "\n";

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

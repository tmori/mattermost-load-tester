<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Gnello\Mattermost\Driver;
use \App\Libs\MattermostUtils;

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

            $team_name = $this->argument('team_name');
            $channel_name = $this->argument('channel_name');
            $display_name = $this->argument('display_name');
            $type = $this->argument('type');
            /*
             * Get TeamId
             */
            $team_id = MattermostUtils::getTeamId($driver, $team_name);

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
            echo "body=" . $result->getBody() . "\n";

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

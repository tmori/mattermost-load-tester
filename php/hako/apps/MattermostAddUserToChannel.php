<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Gnello\Mattermost\Driver;
use \App\Libs\MattermostUtils;

class MattermostAddUserToChannel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mattermost:add_user_to_channel {team_name} {channel_name} {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add user to channel';

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
             * Add User To Team
             */
            $team_id = MattermostUtils::getTeamId($driver, $this->argument('team_name'));
            $channel_id = MattermostUtils::getChannelId($driver, $team_id, $this->argument('channel_name'));
            $user_id = MattermostUtils::getUserId($driver, $this->argument('username'));

            echo "##START: ADD USER TO CHANNEL\n";
            $result = $driver->getChannelModel()->addUser(
                $channel_id,
                [
                    'user_id' => $user_id
                ]
            );
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

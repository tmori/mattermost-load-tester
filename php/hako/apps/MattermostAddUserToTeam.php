<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Gnello\Mattermost\Driver;
use \App\Libs\MattermostUtils;
use \Exception;

class MattermostAddUserToTeam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mattermost:add_user_to_team {team_name} {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add user to team';

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
            $user_id = MattermostUtils::getUserId($driver, $this->argument('username'));

            echo "##START: ADD USER TO TEAM\n";
            $result = $driver->getTeamModel()->addUser(
                $team_id,
                [
                    'team_id' => $team_id,
                    'user_id' => $user_id
                ]
            );
            echo "body=" . $result->getBody() . "\n";


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

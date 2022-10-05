<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Gnello\Mattermost\Driver;
use \App\Libs\MattermostUtils;
use \Exception;

class MattermostCreateTeam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mattermost:create_team {name} {display_name} {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a team';

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
             * Create Team
             */
            echo "##START: CREATE TEAM\n";
            $result = $driver->getTeamModel()->createTeam([
                'name'    => $this->argument('name'), 
                'display_name' => $this->argument('display_name'), 
                'type' => $this->argument('type')
            ]);
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

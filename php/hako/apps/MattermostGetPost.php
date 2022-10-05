<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Gnello\Mattermost\Driver;
use \App\Libs\MattermostUtils;
use \Exception;

class MattermostCreatePost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mattermost:get_post {username} {passwd} {team_name} {channel_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get posts for a channel';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $is_login = False;
        try {
            echo "START: GET POST:" . $this->argument('username') . "\n";
            MattermostUtils::initialize();
            if (MattermostUtils::setAdminUser() != 0) {
                return 1;
            }
            $driver = MattermostUtils::createDriver(
                $this->argument('username'),
                $this->argument('passwd'),
                false
            );
            /*
             * Login
             */
            MattermostUtils::login($driver);
            $is_login = True;

            /*
             * Get ids for Team and channel
             */
            $team_id = MattermostUtils::getTeamId($driver, $this->argument('team_name'));
            $channel_id = MattermostUtils::getChannelId($driver, $team_id, $this->argument('channel_name'));

            $result = $driver->getPostModel()->getPostsForChannel(
                $channel_id,
                []
            );
            echo "body=" . $result->getBody() . "\n";


            /*
             * Logout
             */
            MattermostUtils::logout($driver);
            $is_login = False;
            echo "END: GET POST:" . $this->argument('username') . "\n";
        } catch (\Exception  $e) {
            if ($is_login == True) {
                MattermostUtils::logout($driver);
                $is_login = False;
            }
            echo $e->getMessage().PHP_EOL;
            echo "ERROR: GET POST:" . $this->argument('username') . "\n";
            return 1;
        }

        return 0;
    }
}

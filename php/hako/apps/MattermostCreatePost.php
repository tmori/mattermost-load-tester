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
    protected $signature = 'mattermost:create_post {username} {passwd} {team_name} {channel_name} {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a post';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $is_login = False;
        try {
            echo "START: CREATE POST:" . $this->argument('username') . "\n";
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
             * Add User To Team
             */
            $team_id = MattermostUtils::getTeamId($driver, $this->argument('team_name'));
            $channel_id = MattermostUtils::getChannelId($driver, $team_id, $this->argument('channel_name'));
            $message = $this->argument('message');

            $result = $driver->getPostModel()->createPost(
                [
                    'channel_id' => $channel_id,
                    'message' => $message
                ]
            );
            #echo "body=" . $result->getBody() . "\n";


            /*
             * Logout
             */
            MattermostUtils::logout($driver);
            $is_login = False;
            echo "END: CREATE POST:" . $this->argument('username') . "\n";
        } catch (\Exception  $e) {
            if ($is_login == True) {
                MattermostUtils::logout($driver);
                $is_login = False;
            }
            echo $e->getMessage().PHP_EOL;
            echo "ERROR: CREATE POST:" . $this->argument('username') . "\n";
            return 1;
        }

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Gnello\Mattermost\Driver;
use \App\Libs\MattermostUtils;

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
        try {
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

            /*
             * Add User To Team
             */
            $team_id = MattermostUtils::getTeamId($driver, $this->argument('team_name'));
            $channel_id = MattermostUtils::getChannelId($driver, $team_id, $this->argument('channel_name'));
            $message = $this->argument('message');

            #echo "##START: CREATE POST\n";
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
        } catch (Exception  $e) {
            echo $e->getMessage().PHP_EOL;
        }
        return 0;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Gnello\Mattermost\Driver;
use \App\Libs\MattermostUtils;
use \Exception;

class MattermostGetPostById extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mattermost:get_post_by_id {username} {passwd} {channel_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get posts for a channel by channel_id';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $is_login = False;
        try {
            echo "START: GET POST by id:" . $this->argument('username') . "\n";
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

            $channel_id = $this->argument('channel_id');

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
            echo "END: GET POST by id:" . $this->argument('username') . "\n";
        } catch (\Exception  $e) {
            if ($is_login == True) {
                MattermostUtils::logout($driver);
                $is_login = False;
            }
            echo $e->getMessage().PHP_EOL;
            echo "ERROR: GET POST by id:" . $this->argument('username') . "\n";
            return 1;
        }

        return 0;
    }
}

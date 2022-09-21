<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Gnello\Mattermost\Driver;

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
        return 0;
    }
}

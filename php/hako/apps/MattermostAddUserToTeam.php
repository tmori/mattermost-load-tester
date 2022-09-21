<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Gnello\Mattermost\Driver;

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
        return 0;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Gnello\Mattermost\Driver;

class MattermostTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:MattermostTest {login_id} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Do Mattermost test';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ALL);
        $container = new \Pimple\Container([
            'driver' => [
                'url' => getenv('MATTERMOST_URL'),
                'login_id' => $this->argument('login_id'),
                'password' => $this->argument('password'),
            ],
            'guzzle' => [
                'verify' => false
            ]
        ]);
         
        try {
            $driver = new Driver($container);
            $result = $driver->authenticate();    
            $code = strval($result->getStatusCode());
            $phrase = $result->getReasonPhrase();
            echo "code=${code} : ${phrase}\n";
            echo "token=" . $result->getHeader('Token')[0] ."\n";
            echo $result->getBody();
        } catch (Exception  $e) {
            echo $e->getMessage().PHP_EOL;
        }
        
        return 0;
    }
}

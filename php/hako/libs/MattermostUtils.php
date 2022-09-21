<?php

namespace App\Libs;

use \Gnello\Mattermost\Driver;

class MattermostUtils {
    public static $root_user;
    public static $root_passwd;

    public static function initialize()
    {
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ALL);
        return 0;
    }
    public static function setAdminUser()
    {
        MattermostUtils::$root_user = getenv('MATTERMOST_ROOT_USER');
        MattermostUtils::$root_passwd = getenv('MATTERMOST_ROOT_PASSWD');
        if (MattermostUtils::$root_user == null) {
            echo "ERROR: MATTERMOST_ROOT_USER is not set\n";
            return 1;
        }
        if (MattermostUtils::$root_passwd == null) {
            echo "ERROR: MATTERMOST_ROOT_PASSWD is not set\n";
            return 1;
        }
        return;
    }

    public static function getTeamId($driver, $team_name)
    {
        echo "##START: TeamName=" . $team_name . "\n";
        $result = $driver->getTeamModel()->getTeamByName($team_name);
        $res = $result->getBody();
        $jsonstr =  json_decode($res, true);
        $team_id = $jsonstr['id'];
        echo "team_id=" . $team_id . "\n";
        return $team_id;
    }
    public static function getUserId($driver, $username)
    {
        echo "##START: UserName=" . $username . "\n";
        $result = $driver->getUserModel()->getUserByUsername($username);
        $res = $result->getBody();
        $jsonstr =  json_decode($res, true);
        $user_id = $jsonstr['id'];
        echo "user_id=" . $user_id . "\n";
        return $user_id;
    }
    public static function createDriver($login_id, $password, $verify)
    {
        $container = new \Pimple\Container([
            'driver' => [
                'url' => getenv('MATTERMOST_URL'),
                'login_id' => $login_id,
                'password' => $password,
            ],
            'guzzle' => [
                'verify' => $verify
            ]
        ]);
        return new Driver($container);
    }

    public static function login($driver)
    {
        echo "##START: LOGIN\n";
        $result = $driver->authenticate();    
        $code = strval($result->getStatusCode());
        $phrase = $result->getReasonPhrase();
        echo "code=${code} : ${phrase}\n";
        #echo "token=" . $result->getHeader('Token')[0] ."\n";
        echo "body=" . $result->getBody() . "\n";
    }
    public static function logout($driver)
    {
        echo "##LOGOUT\n";
        $result = $driver->getUserModel()->logoutOfUserAccount();
        $code = strval($result->getStatusCode());
        $phrase = $result->getReasonPhrase();
        echo "code=${code} : ${phrase}\n";
        echo "body=" . $result->getBody() . "\n";
        return true;
    }

}

?>
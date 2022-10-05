<?php

namespace App\Libs;

use \Exception;
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
        $result = $driver->getTeamModel()->getTeamByName($team_name);
        if ($result->getStatusCode() != 200) {
            throw new \Exception('Can not find team_id for ' . $team_name);
        }
        $code = strval($result->getStatusCode());
        $phrase = $result->getReasonPhrase();
        $res = $result->getBody();
        $jsonstr =  json_decode($res, true);
        $team_id = $jsonstr['id'];
        return $team_id;
    }
    public static function getChannelId($driver, $team_id, $channel_name)
    {
        $result = $driver->getChannelModel()->getChannelByName($team_id, $channel_name);
        if ($result->getStatusCode() != 200) {
            throw new \Exception('Can not find channel_id for ' . $channel_name);
        }
        $res = $result->getBody();
        $jsonstr =  json_decode($res, true);
        $channel_id = $jsonstr['id'];
        return $channel_id;
    }

    public static function getUserId($driver, $username)
    {
        #echo "##START: UserName=" . $username . "\n";
        $result = $driver->getUserModel()->getUserByUsername($username);
        if ($result->getStatusCode() != 200) {
            throw new \Exception('Can not find user_id for ' . $username);
        }
        $res = $result->getBody();
        $jsonstr =  json_decode($res, true);
        $user_id = $jsonstr['id'];
        #echo "user_id=" . $user_id . "\n";
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
        #echo "##START: LOGIN\n";
        $result = $driver->authenticate();    
        $code = strval($result->getStatusCode());
        $phrase = $result->getReasonPhrase();
        #echo "code=${code} : ${phrase}\n";
        #echo "token=" . $result->getHeader('Token')[0] ."\n";
        #echo "body=" . $result->getBody() . "\n";
    }
    public static function logout($driver)
    {
        #echo "##LOGOUT\n";
        $result = $driver->getUserModel()->logoutOfUserAccount();
        $code = strval($result->getStatusCode());
        $phrase = $result->getReasonPhrase();
        #echo "code=${code} : ${phrase}\n";
        #echo "body=" . $result->getBody() . "\n";
        return true;
    }

}

?>
<?php

namespace App\Helpers;
use LRedis;

class Socket
{
    public static function send($data)
    {
        $redis = LRedis::connection();
        $redis->publish('message', json_encode($data));
    }
}
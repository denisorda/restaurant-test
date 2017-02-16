<?php

namespace App\Helpers;

use App\User;

class Result
{
    public static function error($code, $text)
    {
        $data = ['error' => $text];
        header("HTTP/1.0 " . $code . " Unprocessable Entity");
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:GET, POST, PUT, DELETE, OPTIONS');
        header('Cache-Control:no-cache');
        header('Content-Type:application/json');
        echo json_encode($data);
        die();
    }
}
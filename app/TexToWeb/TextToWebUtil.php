<?php
/**
 * Created by PhpStorm.
 * User: touhid
 * Date: 07-Oct-19
 * Time: 4:52 PM
 */

namespace App\TexToWeb;


class TextToWebUtil
{

    public static function getRandomNumber($n) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    public static function get8Random(){
        return strtolower ( self::getRandomNumber(8) );
    }


}
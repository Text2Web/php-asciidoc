<?php
/**
 * Created by PhpStorm.
 * User: hmtmc
 * Date: 02/10/2017
 * Time: 07:35 PM
 */

namespace App\TexToWeb;


class PathResolver
{

    public static $THEME_DIR = "content";
    public static $TEXT_TO_WEB_DIR = "text-to-web";


    public static function getTextToWebRoot() {
        return ROOT . DS . self::$TEXT_TO_WEB_DIR;
    }

    public static function getLandingLayout() {
        return self::getLayout("landing.html");
    }

    public static function getLayout($layoutName) {
        return self::getTextToWebRoot() . DS . "theme" . DS . $layoutName;
    }


}
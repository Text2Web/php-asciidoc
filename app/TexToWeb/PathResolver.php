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

    public static $LANDING = "landing";
    public static $OUTLINE = "outline";
    public static $DETAILS = "details";
    public static $VIDEO = "video";
    public static $TOPICS = "topics";


    public static function getTextToWebRoot()
    {
        return ROOT . DS . self::$TEXT_TO_WEB_DIR;
    }

    public static function getTopicsLayout()
    {
        return self::getLayout(self::$TOPICS);
    }

    public static function getLandingLayout()
    {
        return self::getLayout(self::$LANDING);
    }

    public static function getOutlineLayout()
    {
        return self::getLayout(self::$OUTLINE);
    }

    public static function getDetailsLayout()
    {
        return self::getLayout(self::$DETAILS);
    }

    public static function getVideoLayout()
    {
        return self::getLayout(self::$VIDEO);
    }

    public static function getLayout($layoutName)
    {
        return self::getTextToWebRoot() . DS . "theme" . DS . $layoutName . ".html";
    }


}
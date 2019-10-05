<?php
/**
 * Created by PhpStorm.
 * User: hmtmc
 * Date: 02/10/2017
 * Time: 07:35 PM
 */

namespace App\TexToWeb;


use phpDocumentor\Reflection\Types\Self_;

class PathResolver
{

    public static $THEME_DIR = "content";
    public static $TEXT_TO_WEB_DIR = "text-to-web";

    public static $LANDING = "landing";
    public static $OUTLINE = "outline";
    public static $DETAILS = "details";
    public static $VIDEO = "video";
    public static $TOPICS = "topics";
    public static $CONFIG_FILE = "config.json";
    public static $HTML_CONTENT = "html-content";


    public static function getAppRoot()
    {
        return ROOT . DS;
    }


    public static function configFile()
    {
        return self::getAppRoot() . self::$CONFIG_FILE;
    }


    public static function getTextToWebRoot()
    {
        return self::getAppRoot() . self::$TEXT_TO_WEB_DIR;
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

    public static function getThemeDir()
    {
        return self::getTextToWebRoot() . DS . "theme" . DS;
    }

    public static function getThemeCacheDir()
    {
        return self::getThemeDir() . "cache";
    }

    public static function getLayout($layoutName)
    {
        return self::getThemeDir() . $layoutName . ".html";
    }

    public static function getPublicDir()
    {
        return self::getAppRoot() . DS . "public";
    }

    public static function getDefaultThumbs()
    {
        return self::getPublicDir() . DS . "asset" . DS . "images" . DS . "default_thumbs.jpg";
    }

    public static function getHtmlContent()
    {
        return self::getTextToWebRoot() . DS . "html-content" . DS;
    }

    public static function getHtmlContentAssets($path)
    {
        return self::getHtmlContent() . "resource" . DS . $path;
    }


}
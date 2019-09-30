<?php
/**
 * Created by PhpStorm.
 * User: hmtmc
 * Date: 30/09/2019
 * Time: 10:55 PM
 */

namespace App\TexToWeb;


class Config
{

    public $docRoot = null;



    public static function getConfig(){
        $fileAndDirectoryService = new FileAndDirectoryService();
        $configJsonObject = $fileAndDirectoryService->getJsonFromFile(PathResolver::configFile());
        $config = new Config();
        if (isset($configJsonObject->docRoot)){
            $config->docRoot = $configJsonObject->docRoot;
        }else{
            $config->docRoot = PathResolver::getTextToWebRoot() . DS . "html-content";
        }
        return $config;
    }


}
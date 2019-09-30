<?php
/**
 * Created by PhpStorm.
 * User: hmtmc
 * Date: 27/09/2019
 * Time: 01:54 PM
 */

namespace App\TexToWeb;



class TextToWeb
{

    public function getDescriptor($url){
        echo "<pre>";
        $url = rtrim($url,'/');
        $urlFragments = empty($url)? array() : explode("/", $url);
        $config = Config::getConfig();
        $textToWebData = new TextToWebData();
        $fileAndDirectoryService = new FileAndDirectoryService();
        $urlToDir = str_replace("/", DS , $url);
        $urlFragments = array_reverse($urlFragments);
        $descriptorJson = "descriptor.json";
        foreach ($urlFragments as $segment) {
            $path = $config->docRoot . DS . $urlToDir . DS . $descriptorJson;
            if (FileAndDirectoryService::isFile($path)){
                $textToWebData->descriptor = $fileAndDirectoryService->getJsonFromFile($path);
                return $textToWebData;
            }else{
                $urlToDir = substr($urlToDir, 0, strlen($urlToDir) - strlen( DS . $segment));
            }
        }
        $textToWebData->descriptor = $fileAndDirectoryService->getJsonFromFile($config->docRoot . DS . $descriptorJson);
        return $textToWebData;
    }

    public function getPage($url){


        print_r($this->getDescriptor($url));

        $fileAndDirectoryService = new FileAndDirectoryService();
        $layoutPath = PathResolver::getLandingLayout();
        $layout = $fileAndDirectoryService->read($layoutPath);
        $descriptor = '';
        $page = '';
        return $layout;
    }

}
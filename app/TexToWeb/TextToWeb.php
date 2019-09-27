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

    public function getPage($url){
        $fileAndDirectoryService = new FileAndDirectoryService();
        $layoutPath = PathResolver::getOutlineLayout();
        $layout = $fileAndDirectoryService->read($layoutPath);
        $descriptor = '';
        $page = '';
        return $layout;
    }

}
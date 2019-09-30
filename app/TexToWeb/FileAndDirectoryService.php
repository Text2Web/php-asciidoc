<?php
/**
 * Created by PhpStorm.
 * User: hmtmc
 * Date: 02/10/2017
 * Time: 07:08 PM
 */

namespace App\TexToWeb;

use DirectoryIterator;
use stdClass;

class FileAndDirectoryService
{


    public function getJsonFromFile($location){
        $text = $this->read($location);
        if ($text === null){
            return null;
        }else{
            return json_decode($text);
        }
    }

    public function read($filePath){
        if(file_exists($filePath) && self::isFile($filePath)){
            $content = fopen($filePath, "r");
            if(filesize($filePath) && $content){
                $fileContent = fread($content, filesize($filePath));
                fclose($content);
                return $fileContent;
            }
            fclose($content);
            return null;
        }else{
            return null;
        }
    }



    public function formatName($name){
        $splitString = trim($name, ' ');
        $explodeDot = explode('.', $splitString);
        $realName = isset($explodeDot[1])?$explodeDot[1]:$name;
        $realName = ucwords(preg_replace("/[_-]/", " ", $realName));
        return $realName;
    }

    public function getNameOnly($name){
        $splitString = trim($name, ' ');
        $explodeDot = explode('.', $splitString);
        $realName = isset($explodeDot[1])?$explodeDot[1]:$name;
        return $realName;
    }

    public function scanDirectory($location, $recursive = false, $extension = null){

        $list = array();
        $fileData = new stdClass();
        $fileData->name = "";
        $fileData->isDirectory = "";
        $fileData->path = "";

        if (file_exists($location)){
        foreach (new DirectoryIterator($location) as $fileInfo) {
            if ($fileInfo->isDot()){continue;}
            if ($extension === null || $fileInfo->getExtension() === $extension){
                $fileData = new stdClass();
                if ($fileInfo->isDir() && $recursive){
                    $this->scanDirectory($fileInfo->getPathname(), $recursive);
                }
                if ($fileInfo->isDir()){
                    $fileData->isDirectory = true;
                }elseif ($fileInfo->isFile()){
                    $fileData->isDirectory = false;
                }
                $fileData->name = $fileInfo->getFilename();
                $fileData->path = $fileInfo->getPathname();
                array_push($list, $fileData);
            }
        }
        }
        return $list;
    }

    public function scanMenuPool($location)
    {
        $menuList = array();
        if (file_exists($location)){
            foreach (new DirectoryIterator($location) as $fileInfo) {
                if ($fileInfo->isDot()) continue;
                if (
                    $fileInfo->getFilename() === ".idea" ||
                    $fileInfo->getFilename() === ".git" ||
                    $fileInfo->getFilename() === "empty" ||
                    $fileInfo->getFilename() === "topic-resources" ||
                    $fileInfo->getFilename() === "push" ||
                    $fileInfo->getFilename() === "draft" ||
                    $fileInfo->getFilename() === ".gitignore"
                ){
                    continue;
                }
                if ($fileInfo->isFile() && $fileInfo->getExtension() !== "md") continue;
                $menuInformation = new stdClass();
                $menuInformation->fileName = $fileInfo->getFilename();
                $menuInformation->nameOnly = $this->getNameOnly($fileInfo->getFilename());
                $menuInformation->displayName = $this->formatName($fileInfo->getFilename());
                $menuInformation->menuInfo = $this->getMenuInfo($fileInfo->getPathname());
                $menuInformation->lastModified = $fileInfo->getMTime();
                $menuInformation->isFile = $fileInfo->isFile();
                $menuInformation->subMenues = null;
                if ($fileInfo->isDir() && count(glob($fileInfo->getPathname() . DS . "*")) !== 0){
                    $menuInformation->subMenues = $this->scanMenuPool($fileInfo->getPathname());
                }
                array_push($menuList, $menuInformation);
            }
            usort($menuList, function($a, $b)
            {
                return strnatcmp($a->fileName, $b->fileName);
            });
        }
        return $menuList;
    }



    public static function isFile($location){
        return is_file($location);
    }

    public static function isDirectory($location){
        return is_dir($location);
    }

    public static function notExistCreateDir($location){
        if (!file_exists($location)){
           if (!mkdir($location,0755, true)){

           }
        }
    }

    public function delete($path){
        if (file_exists($path)){
           if (is_dir($path)){
               rmdir($path);
           }elseif(is_file($path)){
               unlink($path);
           }
        }
    }

    function isEmptyDir($dirname)
    {
        if (!is_dir($dirname)) return false;
        foreach (scandir($dirname) as $file)
        {
            if (!in_array($file, array('.','..','.svn','.git'))){
                return false;
            }
        }
        return true;
    }

    public function deleteRecursive($path){
        if (file_exists($path)){
            $this->delete($path);
        }
        $path = dirname($path);
        if ($this->isEmptyDir($path)){
            $this->deleteRecursive($path);
        }
    }
}
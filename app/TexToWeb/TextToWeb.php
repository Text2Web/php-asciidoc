<?php
/**
 * Created by PhpStorm.
 * User: hmtmc
 * Date: 27/09/2019
 * Time: 01:54 PM
 */

namespace App\TexToWeb;



use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TextToWeb
{
    private $title = "..:: Bismillah ::..";

    private function urlToUrlKey($url){
        return str_replace("/", "_" , $url);
    }

    public function getTextToWebData($url){
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
        $textToWebData->urlKey = $this->urlToUrlKey($url);
        $textToWebData->url= $url;
        $textToWebData->topicNav= $this->getNavigation($textToWebData->descriptor);
        return $textToWebData;
    }

    public function getNavigation($descriptor){
        $topicNav = [];
        if (isset($descriptor->topics) && is_array($descriptor->topics)){
            $itemIndex = 1;
            $navIndex = 1;
            foreach ($descriptor->topics as $topic){
                $ttwNav = new TTWNav();
                $navKey = "";
                if (isset($topic->seo->title)){
                    $ttwNav->title = $topic->seo->title;
                }else if (isset($topic->name)){
                   $ttwNav->title = $topic->name;
                }else{
                    $ttwNav->title = $this->title;
                }

                if (isset($topic->url)){
                    $ttwNav->url = $topic->url;
                    $navKey = $this->urlToUrlKey($topic->url);
                }else{
                    $ttwNav->url = "#";
                    $navKey = "#-" . $navIndex;
                    $navIndex++;
                }

                if (isset($topic->name)){
                    $ttwNav->name = $topic->name;
                }else{
                    $ttwNav->name = "Nav Item " . $itemIndex;
                    $itemIndex++;
                }

                if (isset($topic->seo)){
                    $ttwNav->seo = $topic->seo;
                }
                $topicNav[$navKey] = $ttwNav;
            }
        }
        return $topicNav;
    }


    public function setupView($textToWebData){
        $descriptor = $textToWebData->descriptor;
        if (isset($descriptor->layout->type)){
            $textToWebData->layout = $descriptor->layout->type . ".html";
        }else{
            $textToWebData->layout = "404.html";
        }
        return $textToWebData;
    }


    public function getPageTitle($textToWebData){
        $title = $this->title;
        if (isset($textToWebData->topicNav[$textToWebData->urlKey]->title)){
            $title = $textToWebData->topicNav[$textToWebData->urlKey]->title;
        }elseif (isset($textToWebData->descriptor->defaultTitle)){
            $title = $textToWebData->descriptor->defaultTitle;
        }
        return $title;
    }



    public function getPageData($textToWebData){
        $textToWebPageData = new TextToWebPageData();
        $textToWebPageData->title = $this->getPageTitle($textToWebData);
        return $textToWebPageData;
    }

    public function getPage($url){
        echo "<pre>";
        $twigLoader = new FilesystemLoader(PathResolver::getThemeDir());
        $twig = new Environment($twigLoader, [
            'cache' => PathResolver::getThemeCacheDir(),
        ]);
        $textToWebData = $this->getTextToWebData($url);
        $textToWebData = $this->setupView($textToWebData);

        print_r($textToWebData);

        try {
            $pageData = $this->getPageData($textToWebData);
            return $twig->render($textToWebData->layout, ['page' => $pageData]);
        } catch (LoaderError $e) {
            return "404";
        } catch (RuntimeError $e) {
            return "404";
        } catch (SyntaxError $e) {
            return "404";
        };
//        echo "</pre>";
    }

}
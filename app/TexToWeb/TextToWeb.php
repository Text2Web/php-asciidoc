<?php
/**
 * Created by PhpStorm.
 * User: hmtmc
 * Date: 27/09/2019
 * Time: 01:54 PM
 */

namespace App\TexToWeb;



use stdClass;
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
        $config = Config::getConfig();
        $url = $this->urlTrim($url);
        $path = $config->docRoot . DS . $this->urlToDir($url);

        $isOutline = false;
        $descriptorFile = "outline.json";
        if (FileAndDirectoryService::isDirectory($path) && FileAndDirectoryService::isFile($path . DS . $descriptorFile)){
            $isOutline = true;
        }else{
            $descriptorFile = "descriptor.json";
        }

        $textToWebData = $this->getDescriptorData($url, $config, $descriptorFile);
        $textToWebData->urlKey = $this->urlToUrlKey($textToWebData->url);

        $navigation = $textToWebData->descriptor;
        if ($isOutline){
            if (isset($textToWebData->descriptor->relatedTopics)){
                $navigation = new stdClass();
                $navigation->topics = $textToWebData->descriptor->relatedTopics;
            }else{
                $navigation = null;
            }
        }



        $textToWebData->topicNav = $this->getNavigation($navigation);
        return $textToWebData;
    }

    public function urlTrim($url){
        $url = $this->urlTrimEndSlash($url);
       return $this->urlTrimStartSlash($url);
    }

    public function urlTrimEndSlash($url){
        return rtrim($url, '/');
    }

    public function urlTrimStartSlash($url){
        return substr($url, 0, 1) === "/" ? substr($url, 1, strlen($url)) : $url;
    }

    public function urlToDir($url){
        return str_replace("/", DS, $url);
    }

    public function getDescriptorData($url, $config, $descriptorFile){
        $urlFragments = empty($url) ? array() : explode("/", $url);
        $textToWebData = new TextToWebData();
        $fileAndDirectoryService = new FileAndDirectoryService();
        $urlToDir = $this->urlToDir($url);
        $urlFragments = array_reverse($urlFragments);
        $descriptorJson = $descriptorFile;
        $textToWebData->url = $url;
        $textToWebData->relativePath = $urlToDir;
        foreach ($urlFragments as $segment) {
            $path = $config->docRoot . DS . $urlToDir . DS . $descriptorJson;
            if (FileAndDirectoryService::isFile($path)) {
                $textToWebData->descriptor = $fileAndDirectoryService->getJsonFromFile($path);
                return $textToWebData;
            } else {
                $urlToDir = substr($urlToDir, 0, strlen($urlToDir) - strlen(DS . $segment));
            }
        }
        $textToWebData->descriptor = $fileAndDirectoryService->getJsonFromFile($config->docRoot . DS . $descriptorJson);
        return $textToWebData;
    }

    public function getNavigation($descriptor){
        $topicNav = new TopicNav();
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

                if (isset($topic->url) && $topic->url != "#") {
                    $ttwNav->url = $topic->url;
                    $urlForKey = $this->urlTrim($topic->url);
                    $navKey = $this->urlToUrlKey($urlForKey);
                } else {
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
                $topicNav->nav[$navKey] = $ttwNav;
                $topicNav->meta[$navKey] = $ttwNav;

                if (isset($topic->childs) && is_array($topic->childs)){
                    $childTopic = new stdClass();
                    $childTopic->topics = $topic->childs;
                    $childs = $this->getNavigation($childTopic);
                    $topicNav->nav[$navKey]->childs = $childs->nav;
                    foreach ($childs->meta as $key => $child){
                        $topicNav->meta[$key] = $child;
                    }
                }
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
        if (isset($textToWebData->topicNav->meta[$textToWebData->urlKey]->title)){
            $title = $textToWebData->topicNav->meta[$textToWebData->urlKey]->title;
        }elseif (isset($textToWebData->descriptor->defaultTitle)){
            $title = $textToWebData->descriptor->defaultTitle;
        }
        return $title;
    }



    public function getPageData($textToWebData){
        $textToWebPageData = new TextToWebPageData();
        $textToWebPageData->title = $this->getPageTitle($textToWebData);
        if (isset($textToWebData->topicNav->nav)){
            $textToWebPageData->nav = $textToWebData->topicNav->nav;
        }
        if (isset($textToWebData->descriptor->blocks)) {
            $textToWebPageData->blocks = $textToWebData->descriptor->blocks;
        }
        if (isset($textToWebData->descriptor->topics)) {
            $textToWebPageData->topics = $textToWebData->descriptor->topics;
        }

        if (isset($textToWebData->layout)) {
            $textToWebPageData->layout = $textToWebData->layout;
        }

        return $textToWebPageData;
    }

    public function getPage($url){
        $twigLoader = new FilesystemLoader(PathResolver::getThemeDir());
        $twig = new Environment($twigLoader, [
//            'cache' => PathResolver::getThemeCacheDir(),
            'cache' => false,
        ]);
        $textToWebData = $this->getTextToWebData($url);
        $textToWebData = $this->setupView($textToWebData);



        try {
            $pageData = $this->getPageData($textToWebData);
//                    echo "<pre>";
//            print_r($textToWebData->topicNav);
//            print_r($textToWebData->descriptor->topics);
//            print_r($pageData);
//            print_r($textToWebData);

            return $twig->render($pageData->layout, ['page' => $pageData]);
        } catch (LoaderError $e) {
            return $e->getMessage();
        } catch (RuntimeError $e) {
            return $e->getMessage();
        } catch (SyntaxError $e) {
            return $e->getMessage();
        };
//        echo "</pre>";
    }

}
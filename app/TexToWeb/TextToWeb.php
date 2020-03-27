<?php
/**
 * Created by PhpStorm.
 * User: hmtmc
 * Date: 27/09/2019
 * Time: 01:54 PM
 */

namespace App\TexToWeb;



use App\TexToWeb\TwigTag\Bootstrap;
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
        $textToWebData->absolutePath = $path;
        $textToWebData->docRoot = $config->docRoot;

        if (isset($textToWebData->descriptor->relatedTopics)){
            $textToWebData->relatedTopicNav = $this->getNavigation($textToWebData->descriptor->relatedTopics, $textToWebData->urlKey);
        }
        if (isset($textToWebData->descriptor->topics)){
            $textToWebData->topicNav = $this->getNavigation($textToWebData->descriptor->topics, $textToWebData->urlKey);
        }
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

    public function getNavigation($topics, $currentUrlKey){
        $topicNav = new TopicNav();
        if (isset($topics) && is_array($topics)){
            $itemIndex = 1;
            $navIndex = 1;
            foreach ($topics as $topic){
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

                if (isset($topic->filePath)){
                    $ttwNav->filePath = $topic->filePath;
                }

                if ($navKey === $currentUrlKey){
                    $ttwNav->active = 'active';
                }

                $topicNav->nav[$navKey] = $ttwNav;
                $topicNav->meta[$navKey] = $ttwNav;

                if (isset($topic->childs) && is_array($topic->childs)){
                    $childs = $this->getNavigation($topic->childs, $currentUrlKey);
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



    public function getPageContent($textToWebData){
        $content = "<h1>Coming Soon.....</h1>";
        if (isset($textToWebData->urlKey) && isset($textToWebData->topicNav->meta[$textToWebData->urlKey])){
            $nav = $textToWebData->topicNav->meta[$textToWebData->urlKey];
            if (isset($nav->filePath)){
               $path = $textToWebData->docRoot . DS . $this->urlToDir($nav->filePath);
            }else{
                $path = $textToWebData->absolutePath . ".html";
            }
            if ($path !== null){
                $fileAndDirectoryService = new FileAndDirectoryService();
                $data = $fileAndDirectoryService->read($path);
                if ($data !== null){
                    return $data;
                }
            }
        }
        return $content;
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
        $textToWebPageData->content = $this->getPageContent($textToWebData);
        return $textToWebPageData;
    }

    public function getPage($url){
        $twigLoader = new FilesystemLoader(PathResolver::getThemeDir());
        $twig = new Environment($twigLoader, [
//            'cache' => PathResolver::getThemeCacheDir(),
            'cache' => false,
        ]);
        $twig->addGlobal('bootstrap', new Bootstrap());
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

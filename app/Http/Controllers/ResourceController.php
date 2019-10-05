<?php

namespace App\Http\Controllers;

use App\TexToWeb\FileAndDirectoryService;
use App\TexToWeb\PathResolver;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class ResourceController extends Controller
{

    private function getPath($path, $default){
       $path = str_replace("/", DS, $path);
        $assets = 'resource';
        if (substr($path, 0, strlen($assets)) === $assets){
            $relativePath = substr($path, strlen($assets) + 1);
            $fullPath = PathResolver::getHtmlContentAssets($relativePath);
            if (FileAndDirectoryService::isFile($fullPath)) {
                return $fullPath;
            }
            echo $fullPath;
            return $fullPath;
        }
        return $default;
    }

    public function index(Request $request)
    {
        $filePath = PathResolver::getDefaultThumbs();
//        $filePath = PathResolver::getImagePath($request->path());
        echo $this->getPath($request->path(), PathResolver::getDefaultThumbs());


//        echo $request->path();
//        echo $filePath;
//        echo $this->getPath($request->path(), PathResolver::getDefaultThumbs());
        die('');

        $imageMeta = getimagesize($filePath);
        $fileInfo = pathinfo($filePath);
        $fileSize = filesize($filePath);
        $fileName = 'no-file.xyz';
        $headers = [];
        if (isset($imageMeta['mime']) && isset($fileInfo['basename'])) {
            $fileName = $fileInfo['basename'];
            $headers = [
                'Content-Type' => $imageMeta['mime'],
                'Content-Length' => $fileSize,
                'Content-Disposition' => 'inline'
            ];
        }
        echo $filePath;
        die('xyz');
        return new BinaryFileResponse($filePath, 200, $headers);
    }


    public function download(){
        $file = PathResolver::getDefaultThumbs();
//        if ($this->startsWith($this->request->url,"resource/images/")){
//            $imagePath = str_replace("resource/images/","",$this->request->url);
//            $imagePath = explode("/", $imagePath);
//            if (count($imagePath) === 2){
//                $imagePath = PathResolver::getTopicImageWithWildcard("*" . $imagePath[0], $imagePath[1]);
//                if (file_exists($imagePath)){
//                    $image = $imagePath;
//                }
//            }
//
//        }
        return response()->download($file);
    }
}

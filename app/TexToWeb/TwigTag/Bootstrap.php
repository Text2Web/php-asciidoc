<?php
/**
 * Created by PhpStorm.
 * User: hmtmc
 * Date: 06/10/2019
 * Time: 11:07 PM
 */

namespace App\TexToWeb\TwigTag;


class Bootstrap
{

    private function getLetNavHtml($navs)
    {
        $html = "";
        if (is_array($navs)){
            foreach ($navs as $data) {
                $html .= "<li class=''><a href='#'>" . $data->name . "</a></li>";
            }
        }
        return $html;
    }

    public function generateLeftNav($nav)
    {
        return $this->getLetNavHtml($nav);
    }

}
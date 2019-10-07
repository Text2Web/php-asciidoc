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
                $liAttr = "";
                $nestedNav = "";
                if (isset($data->childs) && count($data->childs)){
                    $nestedNav = "<ul>";
                    $nestedNav .= $this->getLetNavHtml($data->childs);
                    $nestedNav .= "</ul>";
                }
                $html .= "<li class='" . $data->active . "'><a href='" . $data->url . "'>" . $data->name . "</a>";
                $html .= $nestedNav;
                $html .= "</li>";
            }
        }
        return $html;
    }

    public function generateLeftNav($nav)
    {
        return $this->getLetNavHtml($nav);
    }

}
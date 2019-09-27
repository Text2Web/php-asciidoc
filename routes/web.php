<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->get('/{all:.*}', 'SiteController@index');

//$router->get('bismillah/{urltitle}', function () {
//    return 'Bismillah';
//});
//
//$router->get('my-url/{urltitle}', function () {
//    return 'My URL';
//});
//

//$router->get('/{all:.*}', function ($any) use ($app) {
//    return 'My URL ';
//});
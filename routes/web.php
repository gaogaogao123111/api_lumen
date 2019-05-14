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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/Text/decode','TextController@decode');

$router->post('/Text/efg','TextController@efg');

$router->post('/Text/feiefg','TextController@feiefg');
$router->post('/Text/yansign','TextController@yansign');

$router->post('/User/regadd','UserController@regadd');

//登录
$router->get('/User/login','UserController@login');
$router->post('/User/loginadd','UserController@loginadd');


$router->get('/Text/a','TextController@a');




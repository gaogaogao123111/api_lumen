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
//注册
$router->post('/User/regadd','UserController@regadd');
//登录
$router->post('/User/loginadd','UserController@loginadd');

$router->post('/User/appregadd','UserController@appregadd');
$router->post('/User/apploginadd','UserController@apploginadd');
$router->post('/User/user',[
    'as'=>'profile',
    'uses'=>'UserController@user',
    'middleware'=>'token'
]);
$router->post('/User/goodslist','UserController@goodslist');
$router->post('/User/goodsdetail','UserController@goodsdetail');
$router->post('/User/goodscart','UserController@goodscart');
$router->post('/User/cartlist','UserController@cartlist');
$router->post('/User/cartadd','UserController@cartadd');
$router->post('/User/orderlist','UserController@orderlist');








$router->get('/Text/a','TextController@a');




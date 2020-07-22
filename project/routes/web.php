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

$router->get("test","ApiController@test");

// $router->post("register","ApiController@register");

//$router->post("register","UserController@register");
$router->post('/register','UserController@register');
$router->post('/login','UserController@login');
// $router->get('/user/{id}', 'UserController@show');
$router->get('/details', 'UserController@details');


$router->post('tweet','TweetController@createTweet');
$router->post('editTweet','TweetController@editTweet');
$router->get('getTweet','TweetController@getTweet');
$router->post('delete','TweetController@deleteTweet');

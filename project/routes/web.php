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
$router->post('/logout', 'UserController@logout');
$router->get('users', 'UserController@allUsers');

$router->post('/updateDetails','UserController@updateDetails');
$router->post('/updateProfile','UserController@updateProfile');

// $router->get('/user/{id}', 'UserController@show');
$router->get('/details/{id}', 'UserController@details');


$router->post('createTweet','TweetController@createTweet');
$router->post('editTweet','TweetController@editTweet');
$router->get('getTweet','TweetController@getTweet');

$router->post('deleteTweet','TweetController@deleteTweet');

$router->post('addComment','TweetController@addComment');
$router->post('editComment','TweetController@editComment');
$router->post('deleteComment','TweetController@deleteComment');
$router->get('getComment','TweetController@getComment');

$router->post('like','TweetController@like');
$router->post('Dislike','TweetController@Dislike');
$router->post('retweet','TweetController@retweet');
$router->post('undoRetweet','TweetController@undoRetweet');


$router->post('searchTweet','TweetController@searchTweet');
$router->post('searchComment','TweetController@searchComment');

$router->get('gettenTweet','TweetController@gettenTweet');
$router->get('gettencomment','TweetController@gettencomment');

$router->get('file/profile', 'TweetController@profile');

$router->post('file/update_profile', 'TweetController@update_profile');




// $router->group(['prefix' => 'api'], function () use ($router) {
//     // Matches "/api/register
//     $router->post('register', 'UserController@register');
// });
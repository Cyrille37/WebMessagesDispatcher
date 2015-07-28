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

$app->get('/', 'Controller@home');
$app->get('/about', 'Controller@about');

$app->group(['prefix' => 'api', 'namespace'=>'\App\\Http\\Controllers'], function ($app) {

	$app->get('/', 'ApiController@ping');
	$app->get('stats', 'ApiController@stats');

	$app->post('message_put', 'ApiController@message_put');

/*
	$app->get('messagesSet/{channelId:[0-9]+}', 'App\Http\Controllers\ApiController@getMessagesSet');
	$app->get('messageStatus/{channelId:[0-9]+}/{messageId:[0-9]+}/{status}', 'App\Http\Controllers\ApiController@setMessageStatus');
	$app->get('messageStatus/{channelId:[0-9]+}/{messageId:[0-9]+}/{status}/{comment}', 'App\Http\Controllers\ApiController@setMessageStatus');
	$app->get('textMessage/{channelId:[0-9]+}/{priority:[0-9]+}/{text}', 'App\Http\Controllers\ApiController@addTextMessage');
	$app->get('channelReset/{channelId:[0-9]+}/{maxPriority:[0-9]+}', 'App\Http\Controllers\ApiController@channelReset');
	$app->get('channelDeletePriorized/{channelId:[0-9]+}', 'App\Http\Controllers\ApiController@channelDeletePriorized');
*/

});

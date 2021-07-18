<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->post('/register', 'AuthController@register');

$router->post('/login', 'AuthController@login');

$router->post('/forgot-password', 'AuthController@forgot');

$router->post('/verify-token', 'AuthController@verify_token');

$router->post('/test', 'AuthController@test');

$router->post('/reset-password', 'AuthController@reset');

$router->group(['middleware' => 'auth','namespace' => 'user'], function () use ($router) {
    $router->get('/dashboard', function () {
        // Uses Auth Middleware
    });

    $router->get('user/profile', function () {
        // Uses Auth Middleware
    });
});

$router->group(['middleware' => 'auth','namespace' => 'admin'], function () use ($router) {
    $router->get('/dashboard', function () {
        // Uses Auth Middleware
    });

    $router->get('user/profile', function () {
        // Uses Auth Middleware
    });
});
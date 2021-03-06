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

$router->post('/all-books', 'VisitorController@books');
$router->post('/book', 'VisitorController@book_details');

// $router->post('/all-publishers', 'VisitorController@publishers');

$router->post('/all-blogs', 'VisitorController@blogs');
$router->post('/blog', 'VisitorController@blog_details');


$router->group(['middleware' => 'auth','prefix' => 'user'], function () use ($router) {
    $router->post('/add-review', 'UserReviewController@add');
    $router->post('/review-list', 'UserReviewController@list');
    $router->post('/review-details', 'UserReviewController@details');
    $router->post('/update-review', 'UserReviewController@update');
    $router->post('/delete-review', 'UserReviewController@delete');

    $router->post('/add-comment', 'UserCommentController@add');
    $router->post('/update-comment', 'UserCommentController@update');
    $router->post('/delete-comment', 'UserCommentController@delete');



    $router->post('/order', 'UserOrderController@order'); // 2-08
    $router->post('/order-status', 'UserOrderController@order_status'); // 2-08



});

$router->group(['middleware' => 'auth','prefix' => 'admin'], function () use ($router) {
    $router->post('/add-blog', 'BlogController@add');
    $router->post('/blog-list', 'BlogController@list');
    $router->post('/update-blog', 'BlogController@update');
    $router->post('/delete-blog', 'BlogController@delete');
    $router->post('/blog-details', 'BlogController@details');


    $router->post('/add-publication', 'PublicationController@add');
    $router->post('/publication-list', 'PublicationController@list');
    $router->post('/update-publication', 'PublicationController@update');
    $router->post('/delete-publication', 'PublicationController@delete');
    $router->post('/publication-details', 'PublicationController@details');


    $router->post('/add-book', 'BookController@add');
    $router->post('/book-list', 'BookController@list');
    $router->post('/book-details', 'BookController@details');
    $router->post('/update-book', 'BookController@update');
    $router->post('/delete-book', 'BookController@delete');


    $router->post('/delete-comment', 'AdminGeneralController@delete_comment');

    $router->post('/delete-review', 'AdminGeneralController@delete_review');

});
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/','Auth::registration');
$routes->post('/register','Auth::register');
$routes->post('/login','Auth::authLogin');
$routes->get('/login','Auth::login');
$routes->get('/user/dashboard','Home::dashboard');
$routes->get('/img','Home::img');
$routes->post('/user/update','Home::update');
$routes->get('/logout','Auth::logout');
$routes->post('/user/update/password','Home::updatePassword');






<?php

require_once __DIR__ . '/../vendor/autoload.php';

$pop = new Popcorn\Pop();

$pop->get('/', [
    'controller' => 'Phire\Stats\Controller\IndexController',
    'action'     => 'index'
]);

$pop->get('/login', [
    'controller' => 'Phire\Stats\Controller\IndexController',
    'action'     => 'login'
]);

$pop->get('/logout', [
    'controller' => 'Phire\Stats\Controller\IndexController',
    'action'     => 'logout'
]);

$pop->post('/system', [
    'controller' => 'Phire\Stats\Controller\IndexController',
    'action'     => 'system'
]);

$pop->post('/module', [
    'controller' => 'Phire\Stats\Controller\IndexController',
    'action'     => 'module'
]);

$pop->post('/theme', [
    'controller' => 'Phire\Stats\Controller\IndexController',
    'action'     => 'theme'
]);

$pop->get('*', [
    'controller' => 'Phire\Stats\Controller\IndexController',
    'action'     => 'error'
]);

$pop->run();

<?php
// Configuration for Slim Dependency Injection Container
$container = $app->getContainer();

// Flash messages
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

// Monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// Using Twig as template engine
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('templates', [
        'cache' => false //'cache'
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));

    return $view;
};

// PDO database library
$container['db'] = function($c) {
    $settings = $c->get('settings')['db'];
    $pdo = new PDO("mysql:host=" . $settings['host'] . ";dbname=" . $settings['dbname'], $settings['user'], $settings['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    return $pdo;
};

// Customized Resources Here!

// Homepage Controller
$container['JAPOS\Controller\HomepageController'] = function ($c) {
    return new JAPOS\Controller\HomepageController($c);
};

// Admin Homepage Controller
$container['JAPOS\Controller\AdminHpController'] = function ($c) {
    return new JAPOS\Controller\AdminHpController($c);
};

// // API Controller
// $container['HELLO\Controller\ApiController'] = function ($c) {
//     return new HELLO\Controller\ApiController($c);
// };

// ######### JAMAAH #########
// API Jamaah Controller
$container['JAPOS\Controller\JamaahController'] = function ($c) {
    $jamaahResource = new JAPOS\Resource\JamaahResource($c);
    return new JAPOS\Controller\JamaahController($jamaahResource);
};



<?php

require 'vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->register(
    new Silex\Provider\TwigServiceProvider(),
    array(
        'twig.path' => __DIR__.'/templates',
        'debug' => true
    )
);

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html');
});

$app->run();

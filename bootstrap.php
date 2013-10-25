<?php

require 'vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->get('/', function () {
    return "Hi LVAC people";
});

$app->run();

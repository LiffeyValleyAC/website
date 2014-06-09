<?php
if (!defined('APP_ROOT')) {
    define('APP_ROOT', __DIR__ . '/');
}

// Include the composer stuff
require APP_ROOT . 'vendor/autoload.php';

$app = new Silex\Application();

$app['db'] = new \PDO(
    'sqlite:' . APP_ROOT . 'LVAC.sqlite'
);
$app['db']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$app->register(
    new Silex\Provider\TwigServiceProvider(),
    array(
        'twig.path' => APP_ROOT . 'templates',
        'debug' => true
    )
);

$app->mount('/', new LVAC\BaseControllerProvider());
$app->mount('/news', new LVAC\NewsControllerProvider());

return $app;

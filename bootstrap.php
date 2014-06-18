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

$app->register(new Silex\Provider\SecurityServiceProvider());
$app['security.firewalls'] = array(
    'admin' => array(
        'pattern' => '^/admin/',
        'form' => array(
            'login_path' => '/login',
            'check_path' => '/admin/login_check'
        ),
        'logout' => array(
            'logout_path' => '/admin/logout'
        ),
        'users' => $app->share(function () use ($app) {
            return new \LVAC\UserMapper($app['db']);
        }),
    ),
);

$app->mount('/', new LVAC\BaseControllerProvider());
$app->mount('/news', new LVAC\NewsControllerProvider());
$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html',
        array(
            'error'         => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        )
    );
});

return $app;

<?php
if (!defined('APP_ROOT')) {
    define('APP_ROOT', __DIR__ . '/');
}

// Include the composer stuff
require APP_ROOT . 'vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

if (file_exists(APP_ROOT . 'database.php')) {
    require APP_ROOT . 'database.php';
} else {
    throw new RuntimeException("There is no database configuration");
}

$app->register(
    new Silex\Provider\TwigServiceProvider(),
    array(
        'twig.path' => APP_ROOT . 'templates',
        'debug' => true
    )
);
$app->register(new Silex\Provider\SessionServiceProvider());

$app->mount('/', new LVAC\BaseControllerProvider());
$app->mount('/news', new LVAC\News\ControllerProvider());
$app->mount('/races', new LVAC\Race\ControllerProvider());
$app->mount('/members', new LVAC\Member\ControllerProvider());

$app->get('/login', function () use ($app) {
    return $app['twig']->render('/login.html');
});
$app->post('/login', function (Request $request) use ($app) {
    /*
    if (!isset($request->get('email')) || !isset($request->get('password'))) {
        $error = "You have to fill in your email and password";
        return $app['twig']->render('/login.html', array('error' => $error));
    }
     */
    $email = $request->get('email');
    $password = $request->get('password');

    $member_mapper = new \LVAC\Member\Mapper($app['db']);
    if (false === $member = $member_mapper->checkLogin($email, $password)) {
        // throw some errors
        $error = "The username or password was incorrect";
        return $app['twig']->render('/login.html', array('error' => $error, 'email' => $email));
    }
    $app['session']->set('auth', array('userid' => $member->getId()));
    return $app->redirect('/members');
});
$app->get('/logout', function () use ($app) {
    $app['session']->invalidate();
    return $app->redirect('/');
});

return $app;

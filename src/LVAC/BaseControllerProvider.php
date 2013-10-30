<?php
namespace LVAC;

use Silex\Application;
use Silex\ControllerProviderInterface;

class BaseControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function () use ($app) {
            return $app['twig']->render('index.html');
        });

        return $controllers;
    }
}

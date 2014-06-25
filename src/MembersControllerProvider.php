<?php
namespace LVAC;

use Silex\Application;
use Silex\ControllerProviderInterface;

class MembersControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {
            if (null === $user = $app['session']->get('user')) {
                return $app->redirect('/login');
            }
            return $app['twig']->render('members/index.html');
        });

        return $controllers;
    }
}

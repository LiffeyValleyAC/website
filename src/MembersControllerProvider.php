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
            if (null === $auth = $app['session']->get('auth')) {
                return $app->redirect('/login');
            }
            $mapper = new \LVAC\MemberMapper($app['db']);
            $member = $mapper->getMemberById($auth['userid']);
            return $app['twig']->render('members/index.html', array('nickname' => $member->getNickname()));
        });

        return $controllers;
    }
}

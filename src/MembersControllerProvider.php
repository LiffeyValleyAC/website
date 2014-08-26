<?php
namespace LVAC;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

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

        $controllers->get('/profile', function (Application $app) {
            if (null === $auth = $app['session']->get('auth')) {
                return $app->redirect('/login');
            }
            $mapper = new \LVAC\MemberMapper($app['db']);
            $member = $mapper->getMemberById($auth['userid']);
            return $app['twig']->render('members/profile.html', array(
                'email' => $member->getEmail(),
                'name' => $member->getName(),
                'nickname' => $member->getNickname()
            ));
        });

        $controllers->post('/profile', function (Request $request) use ($app) {
            if (null === $auth = $app['session']->get('auth')) {
                return $app->redirect('/login');
            }
            $name = $request->get('name');
            $nickname = $request->get('nickname');

            $member = new \LVAC\Member();
            $member->setId($auth['userid']);
            $member->setName($name);
            $member->setNickname($nickname);

            $mapper = new \LVAC\MemberMapper($app['db']);
            if (false === $mapper->saveMember($member)) {
                $error = "There was a problem saving your details";
                return $app['twig']->render('/profile.html', array('error' => $error));
            }
            return $app->redirect('/members/profile');
        });

        return $controllers;
    }
}

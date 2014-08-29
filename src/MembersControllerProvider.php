<?php
namespace LVAC;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class MembersControllerProvider implements ControllerProviderInterface
{
    protected $auth;

    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];
        $controllers->before(function (Request $request) use ($app) {
            if (null === $this->auth = $app['session']->get('auth')) {
                return $app->redirect('/login');
            }
        });

        $controllers->get('/', function (Application $app) {
            $mapper = new \LVAC\MemberMapper($app['db']);
            $member = $mapper->getMemberById($this->auth['userid']);
            return $app['twig']->render('members/index.html', array('nickname' => $member->getNickname()));
        });

        $controllers->get('/profile', function (Application $app) {
            $mapper = new \LVAC\MemberMapper($app['db']);
            $member = $mapper->getMemberById($this->auth['userid']);
            return $app['twig']->render('members/profile.html', array(
                'email' => $member->getEmail(),
                'name' => $member->getName(),
                'nickname' => $member->getNickname()
            ));
        });

        $controllers->post('/profile', function (Request $request) use ($app) {
            $name = $request->get('name');
            $nickname = $request->get('nickname');

            $member = new \LVAC\Member();
            $member->setId($this->auth['userid']);
            $member->setName($name);
            $member->setNickname($nickname);

            $mapper = new \LVAC\MemberMapper($app['db']);
            if (false === $mapper->saveMember($member)) {
                $error = "There was a problem saving your details";
                return $app['twig']->render('/profile.html', array('error' => $error));
            }
            return $app->redirect('/members/profile');
        });

        $controllers->get('/training', function (Application $app) {
            return $app['twig']->render('members/training.html');
        });

        return $controllers;
    }
}

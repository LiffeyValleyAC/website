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
            $mapper = new \LVAC\News\NewsMapper($app['db']);
            $news = $mapper->getNews(3);
            return $app['twig']->render('index.html', array('news' => $news));
        });

        $controllers->get('/training', function () use ($app) {
            return $app['twig']->render('training.html');
        });

        $controllers->get('/fixtures', function () use ($app) {
            return $app['twig']->render('fixtures.html');
        });

        $controllers->get('/history', function () use ($app) {
            return $app['twig']->render('history.html');
        });

        $controllers->get('/gallery', function () use ($app) {
            return $app['twig']->render('gallery.html');
        });

        return $controllers;
    }
}

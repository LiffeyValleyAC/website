<?php
namespace LVAC;

use Silex\Application;
use Silex\ControllerProviderInterface;

class NewsControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {
            $mapper = new \LVAC\News\NewsMapper($app['db']);
            $news = $mapper->getNews();
            return $app['twig']->render('news/index.html', array('news' => $news));
        });

        $controllers->get('/{slug}', function (Application $app, $slug) {
            $mapper = new \LVAC\News\NewsMapper($app['db']);
            $news = $mapper->getNewsBySlug($slug);
            return $app['twig']->render('news/news.html', array('news' => $news));
        });

        return $controllers;
    }
}

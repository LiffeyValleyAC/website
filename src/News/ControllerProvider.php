<?php
namespace LVAC\News;

use Silex\Application;
use Silex\ControllerProviderInterface;

class ControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/{page}', function (Application $app, $page) {
            $mapper = new \LVAC\News\Mapper($app['db']);
            $limit = 10;
            $offset = ($page - 1) * $limit;
            $news = $mapper->getNews($limit, $offset);
            $older = $mapper->getPageOlderThan($page);
            $newer = $mapper->getPageNewerThan($page);
            return $app['twig']->render('news/index.html', array('news' => $news, 'older' => $older, 'newer' => $newer));
        })
        ->assert('page', '\d+')
        ->value('page', 1);

        $controllers->get('/{slug}', function (Application $app, $slug) {
            $mapper = new \LVAC\News\Mapper($app['db']);
            $news = $mapper->getNewsBySlug($slug);
            return $app['twig']->render('news/news.html', array('news' => $news));
        });

        $controllers->get('/edit/{slug}', function (Application $app, $slug) {
          $mapper = new \LVAC\News\Mapper($app['db']);
          $news = $mapper->getNewsBySlug($slug);
          return $app['twig']->render('news/edit.html',array('news' => $news));
        });

        $controllers->post('/edit/{slug}', function (Application $app, $slug) {
          $mapper = new \LVAC\News\Mapper($app['db']);
          $news = $mapper->getNewsBySlug($slug);
          $news->setBody($app['request']->get('body'));
          $mapper->save($news);
          return $app->redirect('/news/'.$slug);
        });
        return $controllers;
    }
}

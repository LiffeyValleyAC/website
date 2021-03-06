<?php
namespace LVAC\Gallery;

use Silex\Application;
use Silex\ControllerProviderInterface;

class ControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {
            $mapper = new \LVAC\Gallery\Mapper();
            $albums = $mapper->getAlbumList();
            return $app['twig']->render('gallery/index.html', array('albums' => $albums));
        });

        $controllers->get('/album/{id}', function (Application $app, $id) {
            $mapper = new \LVAC\Gallery\Mapper();
            $photos = $mapper->getAlbumPhotos($id);
            return $app['twig']->render('gallery/album.html', array('photos' => $photos));
        });

        return $controllers;
    }
}

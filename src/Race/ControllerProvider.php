<?php
namespace LVAC\Race;

use Silex\Application;
use Silex\ControllerProviderInterface;

class ControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function () use ($app) {
            $mapper = new \LVAC\Race\Mapper($app['db']);
            $results = $mapper->getResults(5);
            $races = $mapper->getFutureRaces(5);
            return $app['twig']->render('races/index.html', array('results' => $results, 'races' => $races));
        });

        return $controllers;
    }
}

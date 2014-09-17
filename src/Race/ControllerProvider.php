<?php
namespace LVAC\Race;

use Silex\Application;
use Silex\ControllerProviderInterface;
use \Carbon\Carbon as c;

class ControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function () use ($app) {
            $mapper = new \LVAC\Race\Mapper($app['db']);
            $allResults = $mapper->getResults(5);
            $results = $mapper->groupByYear($allResults);
            $races = $mapper->getFutureRaces(5);
            return $app['twig']->render('races/index.html', array('results' => $results, 'races' => $races));
        });

        $controllers->get('/{slug}', function (Application $app, $slug) {
            $mapper = new \LVAC\Race\Mapper($app['db']);
            $race = $mapper->getRaceBySlug($slug);

            if ($race->getDate() < c::today()) {
                $mapper = new \LVAC\Result\Mapper($app['db']);
                $results = $mapper->getResultsOfRace($race->getId());
                return $app['twig']->render('races/result.html', array('race' => $race, 'results' => $results));
            }
            return $app['twig']->render('races/race.html', array('race' => $race));
        });

        return $controllers;
    }
}

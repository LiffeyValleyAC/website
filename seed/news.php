<?php
/**
 * Seed the test database with some news stories using faker
 */
if (!defined('APP_ROOT')) {
    define('APP_ROOT', __DIR__ . '/../');
}

// Include the composer stuff
require APP_ROOT . 'vendor/autoload.php';

$db = new \PDO(
    'sqlite:LVAC.sqlite'
);
$news_mapper = new \LVAC\News\NewsMapper($db);
$faker = Faker\Factory::create();

for ($i = 0; $i < 20; $i++) {
    $news = new \LVAC\News\News();
    $news->setTitle($faker->sentence(rand(3, 10)));
    $news->setBody($faker->paragraph(rand(3, 10)));
    $news->setDate($faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d H:i:s'));
    $news->setSlug($news->createSlug($news->getTitle(), $news->getDate()));

    $news_mapper->save($news);
}

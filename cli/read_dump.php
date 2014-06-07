<?php
if (!defined('APP_ROOT')) {
    define('APP_ROOT', __DIR__ . '/../');
}

// Include the composer stuff
require APP_ROOT . 'vendor/autoload.php';

$file = APP_ROOT . 'seed/LVACEvent.txt';

$db = new \PDO(
    'sqlite:LVAC.sqlite'
);
$db->exec("DROP TABLE IF EXISTS news");
$db->exec("CREATE TABLE news (id INTEGER PRIMARY KEY ASC, title TEXT, body TEXT, date, slug TEXT, location TEXT)");
$news_mapper = new \LVAC\News\NewsMapper($db);

$fp = fopen($file, 'r');
$data = fgetcsv($fp, 0, '|');
while(($data = fgetcsv($fp, 0, '|')) !== false) {
    if(count($data) > 2) {
        $news = new \LVAC\News\News();
        $news->setTitle($data[2]);
        $news->setLocation($data[3]);
        $news->setDate($data[4]);
        $news->setBody($data[5]);
        $news->setSlug();

        $news_mapper->save($news);
    } else {
        echo "This one is wrong: ";
        print_r($data);
    }
}


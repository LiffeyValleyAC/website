<?php
/**
 * Clear out the sqlite database and create the tables that are needed
 */
if (!defined('APP_ROOT')) {
    define('APP_ROOT', __DIR__ . '/../');
}

require APP_ROOT . 'vendor/autoload.php';

$db = new \PDO(
    'sqlite:LVAC.sqlite'
);
$db->exec("DROP TABLE IF EXISTS news");
$db->exec("CREATE TABLE news (id INTEGER PRIMARY KEY ASC, title TEXT, body TEXT, date, slug TEXT, location TEXT)");

$file = APP_ROOT . 'seed/LVACEvent.txt';
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
    }
}

$db->exec("DROP TABLE IF EXISTS users");
$db->exec("CREATE TABLE users (id INTEGER PRIMARY KEY ASC, email TEXT, password TEXT, name TEXT, nickname TEXT)");

$db->exec("INSERT INTO users (id, email, password, name, nickname) VALUES (1, 'fortest@test.fake', '$2y$10\$IYJpRpG3KrBOmmomSPQ9ieVTGQfkjU28iSBwRkZvOAmLXgioIjbxK', 'test', 'fortest')");

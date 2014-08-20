<?php
/**
 * Clear out the sqlite database and create the tables that are needed
 */
if (!defined('APP_ROOT')) {
    define('APP_ROOT', __DIR__ . '/../');
}

require APP_ROOT . 'vendor/autoload.php';

$db = new \PDO(
    //'sqlite:LVAC.sqlite'
    'mysql:host=localhost;dbname=lvac',
    'lvac',
    'lvac'
);
$db->exec("DROP TABLE IF EXISTS news");
$r = $db->exec("
    CREATE TABLE news (
        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255),
        body TEXT,
        date DATE,
        slug TEXT,
        location TEXT
    )
    ");

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
$db->exec("
    CREATE TABLE users (
        id INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
        email VARCHAR(255),
        password VARCHAR(255)
    )
    ");

$db->exec("INSERT INTO users (email, password) VALUES ('fortest@test.fake', '$2y$10\$IYJpRpG3KrBOmmomSPQ9ieVTGQfkjU28iSBwRkZvOAmLXgioIjbxK')");

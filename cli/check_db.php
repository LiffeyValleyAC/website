<?php
if (!defined('APP_ROOT')) {
    define('APP_ROOT', __DIR__ . '/../');
}

// Include the composer stuff
require APP_ROOT . 'vendor/autoload.php';

$db = new \PDO(
    'sqlite:LVAC.sqlite'
);

$mapper = new \LVAC\News\NewsMapper($db);
$news = $mapper->getNews();

foreach($news as $n) {
    echo "{$n->getTitle()}\t--\t{$n->getSlug()}\t--\t{$n->getDate()}\n";
}

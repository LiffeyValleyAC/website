<?php
/**
 * Clear out the sqlite database and create the tables that are needed
 */

$db = new \PDO(
    'sqlite:LVAC.sqlite'
);
$db->exec("DROP TABLE IF EXISTS news");
$db->exec("CREATE TABLE news (id INTEGER PRIMARY KEY ASC, title TEXT, body TEXT, date, slug TEXT, location TEXT)");

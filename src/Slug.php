<?php
namespace LVAC;

class Slug
{
    public static function create($title, $date)
    {
        $date = $date->format('Ymd');
        $title = strtolower($title);
        $title = preg_replace('/[^a-z ]/', '', $title);
        $title = preg_replace('/ /', '-', $title);

        return "{$date}-{$title}";
    }
}

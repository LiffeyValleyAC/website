<?php
namespace LVAC\News;

use \Carbon\Carbon as c;

class News extends \LVAC\Model
{
    protected $title;
    protected $body;
    protected $date;
    protected $slug;
    protected $location;

    public function setDate($date = null)
    {
        if ($date === null) {
            $date = c::now();
        }
        $this->date = c::createFromFormat('Y-m-d H:i:s', $date);
    }

    public function setSlug($slug = null)
    {
        if($slug === null) {
            $slug = $this->createSlug($this->title, $this->date);
        }
        $this->slug = $slug;
    }

    public function createSlug($title, $date)
    {
        $date = $date->format('Ymd');
        $title = strtolower($title);
        $title = preg_replace('/[^a-z ]/', '', $title);
        $title = preg_replace('/ /', '-', $title);

        return "{$date}-{$title}";
    }
}

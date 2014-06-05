<?php
namespace LVAC\News;

use \Carbon\Carbon as c;

class News {
    protected $title;
    protected $body;
    protected $date;
    protected $slug;
    protected $location;

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setDate($date = null)
    {
        if ($date === null) {
            $date = c::now();
        }
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function createSlug($title, $date)
    {
        $date = date('Ymd', strtotime($date));
        $title = strtolower($title);
        $title = preg_replace('/[^a-z ]/', '', $title);
        $title = preg_replace('/ /', '-', $title);

        return "{$date}-{$title}";
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getLocation()
    {
        return $this->location;
    }
}

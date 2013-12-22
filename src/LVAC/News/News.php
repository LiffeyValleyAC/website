<?php
namespace LVAC\News;

use \Carbon\Carbon as c;

class News {
    protected $title;
    protected $body;
    protected $date;

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
}

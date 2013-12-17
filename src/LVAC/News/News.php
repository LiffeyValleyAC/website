<?php
namespace LVAC\News;

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

    public function setDate($date)
    {
        $this->date = $date->format("Y-m-d H:i:s");
    }

    public function getDate()
    {
        return $this->date;
    }
}

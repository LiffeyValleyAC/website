<?php
namespace LVAC\News;

use \LVAC\Slug;
use \Carbon\Carbon as c;

class News extends \LVAC\Model
{
    protected $title;
    protected $body;
    protected $date;
    protected $slug;
    protected $location;
    protected $teaser;

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
            $slug = Slug::create($this->title, $this->date);
        }
        $this->slug = $slug;
    }

    public function getTeaser()
    {
        if (strlen($this->body) > 200) {
            return trim(substr($this->body, 0, 200));
        }
        return $this->body;
    }
}

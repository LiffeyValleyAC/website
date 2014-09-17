<?php
namespace LVAC\Race;

use \Carbon\Carbon as c;

class Race extends \LVAC\Model
{
    protected $id;
    protected $title;
    protected $date;
    protected $slug;
    protected $description;
    protected $report;
    protected $latitude;
    protected $longitude;

    public function setDate($date = null)
    {
        if ($date === null) {
            $date = c::now();
        }
        $this->date = c::createFromFormat('Y-m-d H:i:s', $date);
    }

    public function setMap($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getMap()
    {
        if (isset($this->latitude) && isset($this->longitude)) {
            return array(
                'latitude' => $this->latitude,
                'longitude' => $this->longitude
            );
        }
        return false;
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

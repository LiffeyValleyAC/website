<?php
namespace LVAC\Race;

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
}

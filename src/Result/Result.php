<?php
namespace LVAC\Result;

class Result extends \LVAC\Model
{
    protected $id;
    protected $name;
    protected $duration;
    protected $handicap;
    protected $place;

    public function getDuration()
    {
        return new \DateInterval($this->duration);
    }

    public function getHandicap()
    {
        if ($this->handicap === 'guest') {
            return 'guest';
        }
        return new \DateInterval($this->handicap);
    }
}

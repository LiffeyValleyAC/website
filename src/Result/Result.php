<?php
namespace LVAC\Result;

class Result extends \LVAC\Model
{
    protected $id;
    protected $name;
    protected $duration;
    protected $place;

    public function getDuration()
    {
        return new \DateInterval($this->duration);
    }
}

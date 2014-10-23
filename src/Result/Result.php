<?php
namespace LVAC\Result;

class Result extends \LVAC\Model
{
    protected $id;
    protected $name;
    protected $duration;
    protected $handicap;
    protected $nett;
    protected $place;
    protected $race_id;

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

    public function getNett()
    {
        if ($this->handicap === 'guest') {
            return '';
        }
        $duration = new \DateInterval($this->duration);
        $handicap = new \DateInterval($this->handicap);

        $e = new \DateTime('00:00');
        $f = clone $e;
        $e->add($duration);
        $f->add($handicap);
        return $f->diff($e)->format("%Im %Ss");
    }

    public function setRaceId($raceId)
    {
        $this->race_id = $raceId;
    }

    public function getRaceId()
    {
        return $this->race_id;
    }
}

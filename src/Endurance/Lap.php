<?php

namespace Endurance;

class Lap
{
    /**
     * The start index of the Activity points array
     *
     * @var int
     */
    protected $start;

    /**
     * The end index of the Activity points array
     *
     * @var int
     */
    protected $end;


    public $calories;

    public function __construct($start = 0, $end = 0, $calories = 0)
    {
        $this->setStart($start);
        $this->setEnd($end);
        $this->setCalories($calories);
    }

    public function setStart($start)
    {
        $this->start = (int) $start;
    }

    public function setCalories($calories)
    {
        $this->calories = (int) $calories;
    }

    public function getCalories()
    {
        return $this->calories;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function setEnd($end)
    {
        $this->end = (int) $end;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function filterPoints(array $points)
    {
        return array_slice($points, $this->getStart(), $this->getEnd() - $this->getStart(), true);
    }
}

<?php

namespace Endurance;

class Activity
{
    /**
     * The time when the ride started
     *
     * @var \DateTime
     */
    protected $startTime;

    /**
     * Points
     *
     * @var array
     */
    protected $points = array();

    /**
     * Lap summaries
     *
     * @var array
     */
    protected $laps = array();

    public function setStartTime(\DateTime $startTime)
    {
        $this->startTime = $startTime;
    }

    public function getStartTime()
    {
        return $this->startTime;

    public function addPoint(Point $point)
    {
        $this->points[] = $point;
    }

    public function setPoints(array $points)
    {
        $this->points = array();
        foreach ($points as $point) {
            $this->addPoint($point);
        }
    }

    public function getPoints()
    {
        return $this->points;
    }

    public function addLap(Lap $lap)
    {
        $this->laps[] = $lap;
    }

    public function setLaps(array $laps)
    {
        $this->laps = array();
        foreach ($laps as $lap) {
            $this->addLap($lap);
        }
    }

    public function getLap($index)
    {
        return $this->laps[$index];
    }

    public function setCalories($calories)
    {
        $this->calories = $calories;
    }

    public function getCalories()
    {
        return $this->calories;
    }

    public function getLaps()
    {
        return $this->laps;
    }

    public function getTracks($lapNode)
    {
        $data = array();
        foreach ($lapNode->Track as $track) {
            $data = array_combine($data,(array)$track);
        }
        return $data;
    }
}

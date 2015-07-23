<?php

namespace Endurance\Parser;

use Endurance\Activity;
use Endurance\Lap;
use Endurance\Parser;
use Endurance\Point;

class TCXParser extends Parser
{
    public function parse($file)
    {
        if (!is_file($file)) {
            throw new \Exception(sprintf('Unable to read file "%s"', $file));
        }

        $xml = simplexml_load_file($file);

        return $this->parseData($xml);
    }

    public function parseFromString($data)
    {
        if (empty($data)) {
            throw new \Exception('Empty data to read');
        }

        $xml = simplexml_load_from_string($data);

        return $this->parseData($xml);
    }

    private function parseData($xml)
    {
        $activity = new Activity();

        if (!isset($xml->Activities->Activity)) {
            throw new \Exception(sprintf('Unable to find an Activity', $file));
        }

        // Just parse the first activity
        $activityNode = $xml->Activities->Activity[0];
        $activity->setStartTime(new \DateTime((string) $activityNode->Id));

        $laps = array();
        $calories = 0;
        foreach ($activityNode->Lap as $lapNode) {
            $newLap = $this->parseLap($activity, $lapNode);
            $laps[] = $newLap;
            $calories += $newLap->calories;
        }

        if (count($laps) > 1) {
            // Only set the laps if there is more than one
            $activity->setLaps($laps);
        }
        $activity->setCalories($calories);

        return $activity;
    }

    /**
     * Convert speed value from m/s to km/h
     *
     * @param  float $speed The speed in m/s
     * @return float The speed in km/h
     */
    protected function convertSpeed($speed)
    {
        return $speed * 3.6;
    }

    protected function parseLap(Activity $activity, \SimpleXMLElement $lapNode)
    {
        $startIndex = count($activity->getPoints());
        foreach ($lapNode->Track as $track) {
            $this->parseTrack($activity, $track);
        }
        $calories = (isset($lapNode->Calories)) ? $lapNode->Calories : 0;

        return new Lap($startIndex, count($activity->getPoints()) - 1, $calories);
    }

    protected function parseTrack(Activity $activity, $trackNode)
    {
        foreach ($trackNode->Trackpoint as $trackpointNode) {
            $point = $this->parseTrackpoint($trackpointNode);
            if ($point) {
                $activity->addPoint($point);
            }
        }
    }

    protected function parseTrackpoint(\SimpleXMLElement $trackpointNode)
    {
        // Skip the point if lat/lng not found
        if (!isset($trackpointNode->Position->LatitudeDegrees) || !isset($trackpointNode->Position->LongitudeDegrees)) {
            #return;
        }

        $point = new Point();
        $point->setElevation((float) $trackpointNode->AltitudeMeters);
        $point->setDistance((float) $trackpointNode->DistanceMeters);
        $point->setLatitude((float) $trackpointNode->Position->LatitudeDegrees);
        $point->setLongitude((float) $trackpointNode->Position->LongitudeDegrees);
        $point->time = new \DateTime($trackpointNode->Time);
        #$point->getTime()->modify((string) $trackpointNode->Time);

        if (isset($trackpointNode->HeartRateBpm->Value)) {
            $point->setHeartRate((int) $trackpointNode->HeartRateBpm->Value);
        }

        if (isset($trackpointNode->Extensions->TPX->Speed)) {
            $point->setSpeed($this->convertSpeed((float) $trackpointNode->Extensions->TPX->Speed));
        }

        return $point;
    }
}

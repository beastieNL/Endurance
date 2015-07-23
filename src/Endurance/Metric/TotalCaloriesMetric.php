<?php

namespace Endurance\Metric;

use Endurance\Lap;

class TotalCaloriesMetric 
{
    public function calculate($activity)
    {
        $calories = 0;

        var_dump($activity);
        if (count($laps) > 0 ) {
            foreach ($laps as $lap) {
                if (isset($lap->calories) && $lap->calories > 0) {
                    $calories += $lap->calories;
                }
            }
        } elseif(isset($laps['calories'])) {
            $calories = $laps['calories'];
        }

        return $calories;
    }
}

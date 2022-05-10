<?php

namespace App\Services;

class DaySchedules
{
    private $schedules;

    public function __construct(array $schedules) {
        $this->schedules = $schedules;
    }

    public function get(string $day): array {
        $daySchedule = [];
        foreach ($this->schedules as $key => $schedule) {
            if ($schedule->{0} === $day) {
                $nextSchedule = $schedules[$key + 1] ?? null;
                if ($nextSchedule && $nextSchedule->{0} === '' & $key != 0) {
                    foreach ($schedule as $num => &$value) {
                        if (!$num || !$value) {
                            continue;
                        }
                        $value .= "\n{$nextSchedule->{$num}}";
                    }
                }
                $daySchedule = (array)$schedule;
                break;
            }
        }

        return $daySchedule;
    }
}

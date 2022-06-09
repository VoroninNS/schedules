<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class DaySchedules
{
    private $schedules;

    public function __construct(array $schedules) {
        $this->schedules = $schedules;
    }

    public function get(?string $subgroup, string $day, ?string $subject_filter = null): array {
        $dayData = [];
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
                $dayData = (array)$schedule;
                break;
            }
        }
        if (!$dayData) {
            return $dayData;
        }

        if (!in_array($subject_filter, array_keys(Config::get('constants.SUBJECT_TYPES')))) {
            return self::parse($dayData, $subgroup, $day);
        }

        return self::parse($dayData, $subgroup, $day, $subject_filter);
    }

    public function parse(array $dayData, ?string $subgroup, string $day, ?string $subject_filter = null): array {
        $daySchedules = [];
        foreach (array_flip(Config::get('constants.TIMES')) as $time => $num) {
            $subjects = $dayData[$num + 1];
            if (!$subjects) {
                continue;
            }
            $subjects       = preg_split('@(?<=]\n)@', $subjects);
            $subjectsParser = new SubjectsParser($subjects);
            $daySchedules   = array_merge($daySchedules, $subjectsParser->parse($time, $subgroup, $day, $subject_filter));
        }
        return $daySchedules;
    }
}

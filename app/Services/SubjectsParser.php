<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Config;

class SubjectsParser
{
    const STADIUM = 'Стадиoн';

    private $subjects;

    public function __construct(array $subjects) {
        $this->subjects = $subjects;
    }

    public function parse(string $time, string $subgroup, string $day): array {
        $response = [];
        foreach ($this->subjects as $subject) {
            if (!$this->isSubjectEnabled($subject, $day)) {
                continue;
            }
            if (strpos($subject, 'лабораторные занятия') && !strpos($subject, "($subgroup)")) {
                continue;
            }
            $subject = preg_replace('/(\.)([ .\n]+)(\[.*\])/sU', '$1', $subject, 1);
            $stadium = strpos($subject, self::STADIUM);
            if (!preg_match('|\d+|', $subject) && !$stadium) {
                $subject .= ' онлайн';
            }
            $response[] = [
                'start'   => explode('-', $time)[0],
                'end'     => explode('-', $time)[1],
                'subject' => $subject
            ];
            if (strpos($subject, 'лабораторные занятия')) {
                $nextTime   = explode(
                    '-',
                    Config::get('constants.TIMES')[array_flip(Config::get('constants.TIMES'))[$time] + 1]
                );
                $response[] = ['start' => $nextTime[0], 'end' => $nextTime[1], 'subject' => $subject];
            }
        }

        return $response;
    }

    private function isSubjectEnabled($subject, $day): bool {
        $startDatePos = strpos($subject, '[') + 1;
        $endDatePos   = strpos($subject, ']');
        $dates        = substr($subject, $startDatePos, $endDatePos - $startDatePos);
        $dates        = preg_split('/(\, )|(,\n)/', $dates);
        foreach ($dates as $date) {
            if (!strpos($date, '-') && $date === Carbon::now()->format('d.m')) {
                return true;
            }
            if (strpos($date, '-')) {
                $needle = strpos($date, 'к.н.') ? 'к.н.' : 'ч.н.';
                $date   = str_replace([" $needle", "\n$needle"], '', $date);
                if ($this->inPeriod($date, $day, $needle)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function inPeriod($date, $day, $needle): bool {
        $date   = explode('-', $date);
        $period = CarbonPeriod::create(
            Carbon::createFromFormat('d.m', $date[0])->format('Y-m-d 00:00:00'),
            Carbon::createFromFormat('d.m', $date[1])->format('Y-m-d 00:00:00'),
        )->toArray();
        if ($needle === 'ч.н.') {
            $period = array_filter($period, function ($value, $key) use ($day) {
                return $value->dayName === Config::get("constants.WEEK.$day") && !($key & 1);
            }, ARRAY_FILTER_USE_BOTH);
        }
        $string = Config::get("constants.WEEK.$day") . ' this week';
        if (Carbon::parse($string)->isPast()) {
            $string = Config::get("constants.WEEK.$day") . ' next week';
        }
        if (in_array(Carbon::parse($string), $period)) {
            return true;
        }

        return false;
    }
}

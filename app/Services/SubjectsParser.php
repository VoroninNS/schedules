<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use stringEncode\Exception;

class SubjectsParser
{
    const STADIUM = 'Стадиoн';

    private $subjects;

    public function __construct(array $subjects) {
        $this->subjects = $subjects;
    }

    /**
     * @param string $time
     * @param string|null $subgroup
     * @param string $day
     * @param string|null $subject_filter
     * @return array
     * @throws Exception
     */
    public function parse(string $time, ?string $subgroup, string $day, ?string $subject_filter = null): array {
        $response = [];
        foreach ($this->subjects as $subject) {
            // костыль: чтобы были видны данные, пока нет пар
//            if (!$this->isSubjectEnabled($subject, $day)) {
//                continue;
//            }

            if ($subject_filter && !strpos($subject, Config::get("constants.SUBJECT_TYPES.$subject_filter"))) {
                continue;
            }

            if ($subgroup) {
                if ((strpos($subject, Config::get('constants.SUBJECT_TYPES.laboratory'))
                    && !strpos($subject, "($subgroup)"))) {
                    continue;
                } else {
                    $subject = str_replace("($subgroup).", '', $subject);
                }
            }
            $subject   = preg_replace('/(\.)([ .\n]+)(\[.*\])/sU', '$1', $subject, 1);
            $isStadium = strpos($subject, self::STADIUM);
            if (!preg_match('|\d+|', $subject) && !$isStadium) {
                $subject .= ' онлайн';
            }

            $subjectType = $this->getSubjectType($subject);
            $subject     = str_replace("$subjectType.", '', $subject);

            $pregSubjectPlace = ['/(\n )(?!.*\1)/', '/( )(?!.*\1)/s', '/(\n)(?!.*\1)/', '/   /', '/( \n)(?!.*\1)/'];
            $subjectPlace     = '';
            foreach ($pregSubjectPlace as $preg) {
                $pregResult = preg_split($preg, $subject);
                if (count($pregResult) == 2 && $pregResult[1]) {
                    list($subject, $subjectPlace) = [$pregResult[0], $pregResult[1]];
                    break;
                }
            }
            $subjectPlace = str_replace([' ', '.', "\n"], '', $subjectPlace);

            $subject    = preg_replace('/\./', '', preg_replace('/\n/', ' ', $subject), 1);
            $response[] = [
                'start' => explode('-', $time)[0],
                'end'   => explode('-', $time)[1],
                'name'  => rtrim($subject),
                'type'  => Str::ucfirst($subjectType),
                'place' => rtrim($subjectPlace),
            ];
            if ($subjectType == Config::get('constants.SUBJECT_TYPES.laboratory')) {
                $response = array_merge(
                    $response,
                    $this->doubleTimesForLaboratory($subject, $subjectPlace, $time)
                );
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

    /**
     * @param string $subject
     * @return string
     * @throws Exception
     */
    private function getSubjectType(string $subject): string {
        if (strpos($subject, Config::get('constants.SUBJECT_TYPES.laboratory'))) {
            return Config::get('constants.SUBJECT_TYPES.laboratory');
        }
        if (strpos($subject, Config::get('constants.SUBJECT_TYPES.seminar'))) {
            return Config::get('constants.SUBJECT_TYPES.seminar');
        }
        if (strpos($subject, Config::get('constants.SUBJECT_TYPES.lecture'))) {
            return Config::get('constants.SUBJECT_TYPES.lecture');
        }

        throw new Exception('Не известный тип предмета!');
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

    private function doubleTimesForLaboratory(string $subject, string $subjectPlace, string $time): array {
        $response   = [];
        $nextTime   = explode(
            '-',
            Config::get('constants.TIMES')[array_flip(Config::get('constants.TIMES'))[$time] + 1]
        );
        $response[] = [
            'start' => $nextTime[0],
            'end'   => $nextTime[1],
            'name'  => rtrim($subject),
            'type'  => Str::ucfirst(Config::get('constants.SUBJECT_TYPES.laboratory')),
            'place' => rtrim($subjectPlace),
        ];
        return $response;
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\DaySchedules;
use App\Services\SubjectsParser;
use Illuminate\Support\Facades\Config;
use RandomState\Camelot\Camelot;

class ScheduleControllers extends Controller
{
    public function index() {
        return view('home');
    }

    public function get($group, $subgroup, $day) {
        $schedules   = json_decode(Camelot::lattice(public_path("storage/$group.pdf"))
                                          ->json()
                                          ->extract()[0]);
        $daySchedule = (new DaySchedules($schedules))->get($day);
        if (!$daySchedule) {
            dd('нет пар');
        }

        $response = [];
        foreach (array_flip(Config::get('constants.TIMES')) as $time => $num) {
            $subjects = $daySchedule[$num + 1];
            if (!$subjects) {
                continue;
            }
            $subjects = preg_split('@(?<=]\n)@', $subjects);
            $parser   = new SubjectsParser($subjects);
            $response = array_merge($response, $parser->parse($time, $subgroup, $day));
        }

        if (isset($_GET['dump']) && $_GET['dump'] == 'yes') {
            dd($response);
        }
        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }


    public function allGroup() {
        $schedules = glob(public_path('storage/') . '*.pdf');
        $response  = [];
        foreach ($schedules as $schedule) {
            $schedule   = (explode('/', $schedule));
            $response[] = str_replace('.pdf', '', array_pop($schedule));
        }

        if (isset($_GET['dump']) && $_GET['dump'] == 'yes') {
            dd($response);
        }
        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }
}

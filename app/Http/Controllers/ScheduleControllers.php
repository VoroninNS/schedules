<?php

namespace App\Http\Controllers;

use App\Services\DaySchedules;
use Illuminate\Support\Facades\Config;
use RandomState\Camelot\Camelot;

class ScheduleControllers extends Controller
{
    public function index() {
        return view('home');
    }

    public function byDay($group, $subgroup, $day) {
        $schedules   = json_decode(Camelot::lattice(public_path("storage/$group.pdf"))
                                          ->json()
                                          ->extract()[0]);
        $daySchedule = (new DaySchedules($schedules))->get($subgroup, $day);
        if (isset($_GET['dump']) && $_GET['dump'] == 'yes') {
            dd($daySchedule);
        }
        return json_encode($daySchedule, JSON_UNESCAPED_UNICODE);
    }

    public function byWeek($group, $subgroup) {
        $weekSchedules = [];
        $schedules     = json_decode(Camelot::lattice(public_path("storage/$group.pdf"))
                                            ->json()
                                            ->extract()[0]);
        foreach (Config::get('constants.WEEK') as $day_ru => $day_en) {
            $weekSchedules[$day_en] = (new DaySchedules($schedules))->get($subgroup, $day_ru);
        }
        if (isset($_GET['dump']) && $_GET['dump'] == 'yes') {
            dd($weekSchedules);
        }
        return json_encode($weekSchedules, JSON_UNESCAPED_UNICODE);
    }

    public function allGroups() {
        $schedules = glob(public_path('storage/') . '*.pdf');
        $allGroups = [];
        foreach ($schedules as $schedule) {
            $schedule    = (explode('/', $schedule));
            $allGroups[] = str_replace('.pdf', '', array_pop($schedule));
        }

        if (isset($_GET['dump']) && $_GET['dump'] == 'yes') {
            dd($allGroups);
        }
        return json_encode($allGroups, JSON_UNESCAPED_UNICODE);
    }
}

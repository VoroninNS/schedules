<?php

namespace App\Http\Controllers;

use App\Services\DaySchedules;
use Illuminate\Support\Facades\Config;
use RandomState\Camelot\Camelot;

class ScheduleControllers extends Controller
{
    const STUDENT_SCHEDULES_PUBLIC_PATH = 'storage/student/';
    const TEACHER_SCHEDULES_PUBLIC_PATH = 'storage/teachers/';

    public function index() {
        return view('home');
    }

    public function teacherByDay($teacher, $day) {
        $schedules   = json_decode(Camelot::lattice(public_path(self::TEACHER_SCHEDULES_PUBLIC_PATH . $teacher . '.pdf'))
                                          ->json()
                                          ->extract()[0]);
        $daySchedule = (new DaySchedules($schedules))->get(null, $day);
        if (isset($_GET['dump']) && $_GET['dump'] == 'yes') {
            dd($daySchedule);
        }

        $response = [
            'success' => true,
            'data'    => $daySchedule,
        ];
        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function studentByDay($group, $subgroup, $day) {
        $schedules   = json_decode(Camelot::lattice(public_path(self::STUDENT_SCHEDULES_PUBLIC_PATH . $group . '.pdf'))
                                          ->json()
                                          ->extract()[0]);
        $daySchedule = (new DaySchedules($schedules))->get($subgroup, $day);
        if (isset($_GET['dump']) && $_GET['dump'] == 'yes') {
            dd($daySchedule);
        }

        $response = [
            'success' => true,
            'data'    => $daySchedule,
        ];
        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function studentByWeek($group, $subgroup) {
        $weekSchedules = [];
        $schedules     = json_decode(Camelot::lattice(public_path(self::STUDENT_SCHEDULES_PUBLIC_PATH . $group . '.pdf'))
                                            ->json()
                                            ->extract()[0]);
        foreach (Config::get('constants.WEEK') as $day_ru => $day_en) {
            $weekSchedules[$day_en] = (new DaySchedules($schedules))->get($subgroup, $day_ru);
        }
        if (isset($_GET['dump']) && $_GET['dump'] == 'yes') {
            dd($weekSchedules);
        }

        $response = [
            'success' => true,
            'data'    => $weekSchedules,
        ];
        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function teacherByWeek($teacher) {
        $weekSchedules = [];
        $schedules     = json_decode(Camelot::lattice(public_path(self::TEACHER_SCHEDULES_PUBLIC_PATH . $teacher . '.pdf'))
                                            ->json()
                                            ->extract()[0]);
        foreach (Config::get('constants.WEEK') as $day_ru => $day_en) {
            $weekSchedules[$day_en] = (new DaySchedules($schedules))->get(null, $day_ru);
        }
        if (isset($_GET['dump']) && $_GET['dump'] == 'yes') {
            dd($weekSchedules);
        }

        $response = [
            'success' => true,
            'data'    => $weekSchedules,
        ];
        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function allGroups() {
        $allGroups = self::getName(self::STUDENT_SCHEDULES_PUBLIC_PATH);

        if (isset($_GET['dump']) && $_GET['dump'] == 'yes') {
            dd($allGroups);
        }

        $response = [
            'success' => true,
            'data'    => $allGroups,
        ];
        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function allTeachers() {
        $allTeachers = self::getName(self::TEACHER_SCHEDULES_PUBLIC_PATH);

        if (isset($_GET['dump']) && $_GET['dump'] == 'yes') {
            dd($allTeachers);
        }

        $response = [
            'success' => true,
            'data'    => $allTeachers,
        ];
        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function getName(string $schedulesPublicPath): array {
        $schedules = glob(public_path($schedulesPublicPath) . '*.pdf');
        $allName   = [];
        foreach ($schedules as $schedule) {
            $schedule  = (explode('/', $schedule));
            $name = str_replace('.pdf', '', array_pop($schedule));
            $allName[] = str_replace('_', ' ', $name);
        }

        return $allName;
    }
}

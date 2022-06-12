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
//        $schedules = json_decode(Camelot::lattice(public_path(self::TEACHER_SCHEDULES_PUBLIC_PATH . $teacher . '.pdf'))
//                                        ->json()
//                                        ->extract()[0]);
//
//        $subject_filter = $_GET['subject_filter'] ?? null;
//        $daySchedule    = (new DaySchedules($schedules))->get(null, $day, $subject_filter);
//        if (isset($_GET['dump']) && $_GET['dump'] == 'yes') {
//            dd($daySchedule);
//        }
//
        $daySchedule = Config::get('constants.TEACHER_BY_DAY_MOCK');
        $response = [
            'success' => true,
            'data'    => $daySchedule,
        ];

        return response()->json($response);
    }

    public function studentByDay($group, $subgroup, $day) {
        $schedules      = json_decode(Camelot::lattice(public_path(self::STUDENT_SCHEDULES_PUBLIC_PATH . $group . '.pdf'))
                                             ->json()
                                             ->extract()[0]);
        $subject_filter = $_GET['subject_filter'] ?? null;
        $daySchedule    = (new DaySchedules($schedules))->get($subgroup, $day, $subject_filter);
        if (isset($_GET['dump']) && $_GET['dump'] == 'yes') {
            dd($daySchedule);
        }

        $response = [
            'success' => true,
            'data'    => $daySchedule,
        ];

        return response()->json($response);
    }

    public function studentByWeek($group, $subgroup) {
        $weekSchedules  = [];
        $subject_filter = $_GET['subject_filter'] ?? null;
        $schedules      = json_decode(Camelot::lattice(public_path(self::STUDENT_SCHEDULES_PUBLIC_PATH . $group . '.pdf'))
                                             ->json()
                                             ->extract()[0]);
        foreach (Config::get('constants.WEEK') as $day_ru => $day_en) {
            $weekSchedules[$day_ru] = (new DaySchedules($schedules))->get($subgroup, $day_ru, $subject_filter);
        }
        if (isset($_GET['dump']) && $_GET['dump'] == 'yes') {
            dd($weekSchedules);
        }

        $response = [
            'success' => true,
            'data'    => $weekSchedules,
        ];

        return response()->json($response);
    }

    public function teacherByWeek($teacher) {
//        $weekSchedules  = [];
//        $subject_filter = $_GET['subject_filter'] ?? null;
//        $schedules      = json_decode(Camelot::lattice(public_path(self::TEACHER_SCHEDULES_PUBLIC_PATH . $teacher . '.pdf'))
//                                             ->json()
//                                             ->extract()[0]);
//        foreach (Config::get('constants.WEEK') as $day_ru => $day_en) {
//            $weekSchedules[$day_en] = (new DaySchedules($schedules))->get(null, $day_ru, $subject_filter);
//        }
//        if (isset($_GET['dump']) && $_GET['dump'] == 'yes') {
//            dd($weekSchedules);
//        }

        $weekSchedules = Config::get('constants.TEACHER_BY_WEEK_MOCK');
        $response = [
            'success' => true,
            'data'    => $weekSchedules,
        ];

        return response()->json($response);
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

        return response()->json($response);
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

        return response()->json($response);
    }

    public function getName(string $schedulesPublicPath): array {
        $schedules = glob(public_path($schedulesPublicPath) . '*.pdf');
        $allName   = [];
        foreach ($schedules as $schedule) {
            $schedule  = (explode('/', $schedule));
            $name      = str_replace('.pdf', '', array_pop($schedule));
            $allName[] = str_replace('_', ' ', $name);
        }

        return $allName;
    }
}

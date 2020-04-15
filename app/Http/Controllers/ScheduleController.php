<?php

namespace App\Http\Controllers;

use App\Models\Cabinet;
use App\Models\Schedule;
use App\Models\ScheduleLesson;
use App\Models\ScheduleSubject;
use App\Models\ScheduleWeek;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psy\Util\Json;

class ScheduleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $schedules = Schedule::all();

        return view('schedule.index', compact('schedules'));
    }

    public function create()
    {
        $_schoolClasses = SchoolClass::withCurrentSchool()->get();
        $_teachers = Teacher::withCurrentSchool()->get();
        $cabinets = Cabinet::withCurrentSchool()->get();
        $subjects = Subject::withCurrentSchool()->get();

        $teachers = [];
        foreach($_teachers as $teacher) {
            $teachers[] = [
                'id' => $teacher->id,
                'name' => $teacher->user->name,
            ];
        }

        $schoolClasses = [];
        foreach($_schoolClasses as $schoolClasse) {
            $schoolClasses[] = [
                'id' => $schoolClasse->id,
                'name' => $schoolClasse->title,
            ];
        }

        return view('schedule.form', compact(
            'schoolClasses',
            'subjects',
            'teachers',
            'cabinets'
            ));
    }

    public function store(Request $request)
    {

        $scheduleId = $request->json('scheduleId');
        $dateFrom = $request->json('dateFrom');
        $dateTo = $request->json('dateTo');
        $classId = $request->json('classId');
        $subjectsForSchedule = $request->json('subjectsForSchedule');
        $scheduledWeekdays = $request->json('scheduleWeekday');
        $scheduledSubjects= $request->json('schedule');

        $schoolClass = SchoolClass::find($classId);

        $schedule = null;
        if ($scheduleId) {
            $schedule = Schedule::withCurrentSchool()->find($scheduleId);
        }
        if (!$schedule) {
            $schedule = new Schedule();
        }
        $schedule->dateFrom = Carbon::createFromTimeString($dateFrom);
        $schedule->dateTo = Carbon::createFromTimeString($dateTo);
        $schedule->school()->associate(Auth::user()->currentSchool());
        $schedule->schoolClass()->associate($schoolClass);

        $schedule->save();

        foreach ($scheduledWeekdays as $scheduledWeekday) {
            foreach ($scheduledWeekday as $lessonNumber => $lesson) {
                if ($lesson['subjectId']) {
                    list($subjectId, $teacherId, $cabinetId) = explode('-', $lesson['subjectId']);
                    $subject = Subject::find($subjectId);
                    $teacher = Teacher::find($teacherId);
                    $cabinet = Cabinet::find($cabinetId);
                    $scheduleWeekday = new ScheduleWeek([
                        'weekday' => $lesson['weekday'],
                        'lesson' => $lesson['numOfLesson'],
                    ]);
                    $scheduleWeekday->school()->associate(Auth::user()->currentSchool());
                    $scheduleWeekday->schedule()->associate($schedule);
                    $scheduleWeekday->subject()->associate($subject);
                    $scheduleWeekday->teacher()->associate($teacher);
                    $scheduleWeekday->cabinet()->associate($cabinet);
                    $scheduleWeekday->save();
                }
            }
        }

        foreach ($subjectsForSchedule as $subjectForSchedule) {
            list($subjectId, $teacherId, $cabinetId) = explode('-', $subjectForSchedule);
            $subject = Subject::find($subjectId);
            $teacher = Teacher::find($teacherId);
            $cabinet = Cabinet::find($cabinetId);

            $scheduleSubject = new ScheduleSubject;
            $scheduleSubject->school()->associate(Auth::user()->currentSchool());
            $scheduleSubject->schedule()->associate($schedule);
            $scheduleSubject->subject()->associate($subject);
            $scheduleSubject->teacher()->associate($teacher);
            $scheduleSubject->cabinet()->associate($cabinet);
            $scheduleSubject->save();
        }

        foreach ($scheduledSubjects as $scheduledSubject) {
            if ($scheduledSubject['subjectId']) {
                $subject = Subject::find($scheduledSubject['subjectId']);
                $teacher = Teacher::find($scheduledSubject['teacherId']);
                $cabinet = Cabinet::find($scheduledSubject['cabinetId']);
                $scheduleLesson = new ScheduleLesson([
                    'date' => Carbon::createFromFormat('Y-m-d', $scheduledSubject['date'])->format('Y-m-d'),
                    'lesson_number' => $scheduledSubject['lesson'],
                ]);
                $scheduleLesson->school()->associate(Auth::user()->currentSchool());
                $scheduleLesson->schedule()->associate($schedule);
                $scheduleLesson->subject()->associate($subject);
                $scheduleLesson->teacher()->associate($teacher);
                $scheduleLesson->cabinet()->associate($cabinet);
                $scheduleLesson->save();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Schedule::find($id)->delete();
        return redirect()->route('schedule.index')
            ->with('success','Schedule deleted successfully');
    }

    public function edit($id)
    {
        $_schoolClasses = SchoolClass::withCurrentSchool()->get();
        $_teachers = Teacher::withCurrentSchool()->get();
        $cabinets = Cabinet::withCurrentSchool()->get();
        $subjects = Subject::withCurrentSchool()->get();

        $teachers = [];
        foreach($_teachers as $teacher) {
            $teachers[] = [
                'id' => $teacher->id,
                'name' => $teacher->user->name,
            ];
        }

        $schoolClasses = [];
        foreach($_schoolClasses as $schoolClasse) {
            $schoolClasses[] = [
                'id' => $schoolClasse->id,
                'name' => $schoolClasse->title,
            ];
        }

        $scheduleData = Schedule::with(['scheduleSubjects', 'scheduleWeek', 'scheduleLessons'])->find($id);

        return view('schedule.form', compact(
            'scheduleData',
            'schoolClasses',
            'subjects',
            'teachers',
            'cabinets'
        ));
    }
}

<?php


namespace App\Http\Controllers;


use App\Models\DictionarySubjects;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Hash;


class TeachersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $teachers = Teacher::whereHas('school', function($query) {
            $query->where('id', Auth::user()->currentSchool()->id);
        })
            ->with(['user' => function ($q) {
                $q->orderBy('name', 'ASC');
            }])
            ->with('subjects')
            ->get();

        return view('teachers.index',compact('teachers'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $usersForTeacher = User::whereHas('schools', function($query) {
            $query->where(School::TABLE . '.id', Auth::user()->currentSchool()->id);
        })
       -> whereHas('roles', function($query) {
            $roles = Role::whereIn('name', ['Учитель'])->get();
            $roles = $roles->pluck('id')->all();

            $query->whereIn('id', $roles);
        })
            ->orderBy('name','ASC')
            ->get()
            ->pluck('name', 'id');

        $subjects = Subject::whereHas('school', function($query) {
            $query->where('id', Auth::user()->currentSchool()->id);
        })
            ->orderBy('title','ASC')
            ->get();

        return view('teachers.create', compact('subjects', 'usersForTeacher'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [

        ]);

        $input = $request->all();

        $teacher = new Teacher($input);
        $teacher->school()
            ->associate(Auth::user()->currentSchool());
        $userSelectedForTeacher = User::find($input['userId']);
        $teacher->user()
            ->associate($userSelectedForTeacher);

        $teacher->save();
        $teacher->subjects()->syncWithoutDetaching($input['subjects']);

        return redirect()->route('teachers.index')
            ->with('success','Teacher added successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $teacher = Teacher::whereHas('school', function($query) {
            $query->where(School::TABLE . '.id', Auth::user()->currentSchool()->id);
        })
            ->find($id);

        $teacherSubjects = $teacher->subjects->pluck('id')->all();

        $subjects = Subject::whereHas('school', function($query) {
            $query->where('id', Auth::user()->currentSchool()->id);
        })
            ->orderBy('title','ASC')
            ->get();


        return view('teachers.edit', compact('teacher', 'teacherSubjects','subjects'));

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [

        ]);

        $input = $request->all();

        $teacher= Teacher::whereHas('school', function($query) {
        $query->where(School::TABLE . '.id', Auth::user()->currentSchool()->id);
    })
        ->find($id);

        $teacher->subjects()->sync($input['subjects']);



        return redirect()->route('teachers.index')
            ->with('success','Teacher updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Teacher::find($id)->delete();
        return redirect()->route('teachers.index')
            ->with('success','Teacher deleted successfully');
    }
}

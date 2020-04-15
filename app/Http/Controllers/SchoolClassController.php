<?php


namespace App\Http\Controllers;


use App\Models\School;
use App\Models\SchoolClass;
use App\Models\User;
use App\Models\Pupil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Hash;


class SchoolClassController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = 15;
        $data = SchoolClass::whereHas('school', function($query) {
            $query->where('id', Auth::user()->currentSchool()->id);
        })->orderBy('id','DESC')->paginate($perPage);
        return view('classes/index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * $perPage);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('classes/create');
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
            'number' => 'required',
            'letter' => 'required',
        ]);

        $input = $request->all();

        DB::transaction(function () use ($input) {
            $class = new SchoolClass($input);
            $class->school()
                ->associate(Auth::user()->currentSchool());
            $class->save();
        });

        return redirect()->route('classes.index')
            ->with('success','Class created successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schoolClass = SchoolClass::with('pupils')->find($id);

        return view('classes/show',compact('schoolClass'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $schoolClass = SchoolClass::find($id);

        return view('classes.edit',compact('schoolClass'));
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
            'number' => 'required',
            'letter' => 'required',
        ]);

        $input = $request->all();

        $school = SchoolClass::find($id);
        $school->update($input);


        return redirect()->route('classes.index')
            ->with('success','Class updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SchoolClass::find($id)->delete();
        return redirect()->route('classes.index')
            ->with('success','Class deleted successfully');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bindPupil($classId, Request $request)
    {
        $usersForPupil = User::whereHas('schools', function($query) {
            $query->where(School::TABLE . '.id', Auth::user()->currentSchool()->id);
        })
            -> whereHas('roles', function($query) {
                $roles = Role::whereIn('name', ['Ученик'])->get();
                $roles = $roles->pluck('id')->all();

                $query->whereIn('id', $roles);
            })
            ->orderBy('name','ASC')
            ->get()
            ->pluck('name', 'id');


        return view('pupils/create',compact('usersForPupil', 'classId'));
    }

    public function storePupil($classId,Request $request)
    {
        $this->validate($request, [
            'userId' => 'required',
        ]);


        $input = $request->all();
        $pupil = new Pupil($input);
        $pupil->school()
            ->associate(Auth::user()->currentSchool());
        $userSelectedForPupil = User::find($input['userId']);
        $pupil->user()
            ->associate($userSelectedForPupil);
        $pupil->schoolClass()->associate($classId);
        $pupil->save();


        return redirect()->route('classes.show',[$classId])
            ->with('success','Pupil added successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyPupil($classId, $id)
    {
        Pupil::find($id)->delete();
        return redirect()->route('classes.show',[$classId])
            ->with('success','Pupil deleted successfully');
    }
}

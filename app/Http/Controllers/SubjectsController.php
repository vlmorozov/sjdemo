<?php


namespace App\Http\Controllers;


use App\Models\DictionarySubjects;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Hash;


class SubjectsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subjects = Subject::whereHas('school', function($query) {
            $query->where('id', Auth::user()->currentSchool()->id);
        })->orderBy('title','ASC')->get();

        return view('subjects.index',compact('subjects'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dictionarySubjects = DictionarySubjects::all()->pluck('title', 'title');

        return view('subjects.create', compact('dictionarySubjects'));
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
            'title' => 'required',
        ]);

        $input = $request->all();

        $subject = new Subject($input);
        $subject->school()
            ->associate(Auth::user()->currentSchool());
        $subject->save();

        return redirect()->route('subjects.index')
            ->with('success','Subject created successfully');
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
        $subject = Subject::find($id);

        return view('subjects.edit',compact('subject'));
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
            'title' => 'required',
        ]);

        $input = $request->all();

        $subject = Subject::find($id);
        $subject->update($input);


        return redirect()->route('subjects.index')
            ->with('success','Subject updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Subject::find($id)->delete();
        return redirect()->route('subjects.index')
            ->with('success','Subject deleted successfully');
    }
}

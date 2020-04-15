<?php


namespace App\Http\Controllers;


use App\Models\ClassOfSchool;
use Illuminate\Http\Request;
use DB;
use Hash;


class ClassOfSchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = ClassOfSchool::orderBy('id','DESC')->paginate(5);
        return view('schools.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('schools.create');
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
            'sysname' => 'required|unique:schools,sysname',
            'title' => 'required',
        ]);


        $input = $request->all();


        $school = School::create($input);


        return redirect()->route('schools.index')
            ->with('success','School created successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $school = School::find($id);
        return view('schools.show',compact('school'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $school = School::find($id);

        return view('school.edit',compact('school'));
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
            'sysname' => 'required|unique:schools,sysname',
            'title' => 'required',
        ]);

        $input = $request->all();

        $school = School::find($id);
        $school->update($input);


        return redirect()->route('schools.index')
            ->with('success','School updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        School::find($id)->delete();
        return redirect()->route('schools.index')
            ->with('success','School deleted successfully');
    }
}

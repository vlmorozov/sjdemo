<?php


namespace App\Http\Controllers;


use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use DB;
use Hash;


class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        $sortField =  $request->get('sort','created_at');
        $sortDirection =  $request->get('direction','asc');

        $columns = [
            ['title' => 'ID', 'field' => 'id', 'sort' => false],
            ['title' => 'Sysname', 'field' => 'sysname', 'sort' => false],
            ['title' => 'Title', 'field' => 'title', 'sort' => true],
            ['title' => 'Owner', 'field' => 'owner', 'sort' => true],
            ['title' => 'Address', 'field' => 'address', 'sort' => true],
            ['title' => 'Phone', 'field' => 'phone', 'sort' => true],
            ['title' => 'Action', 'field' => 'action', 'sort' => false, 'width' => '280px']
        ];


        $data = School::with('owner')->orderBy($sortField, $sortDirection)->paginate(5);
        return view('schools.index',[
            'data' => $data,
            'columns' => $columns,
            'sort' => $sortField,
            'direction' => $sortDirection,
        ])
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
            'adress' => 'required',
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
        $school = School::with('owner')->find($id);
        $allUsers = User::whereHas('roles', function($query) {
            $roles = Role::whereIn('name', ['Ученик', 'Родитель'])->get();
            $roles = $roles->pluck('id')->all();

            $query->whereNotIn('id', $roles);
        })->get();

        $owners = $allUsers->pluck('name', 'id')->all();

        return view('schools.edit',compact('school', 'owners'));
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
            'sysname' => 'required',
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

<?php


namespace App\Http\Controllers;


use App\Models\Cabinet;
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


class CabinetsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cabinets = Cabinet::whereHas('school', function($query) {
            $query->where('id', Auth::user()->currentSchool()->id);
        })->orderBy('title','ASC')->get();

        return view('cabinets.index',compact('cabinets'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('cabinets.create');
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
            'number' => 'required',
        ]);

        $input = $request->all();

        $cabinet = new Cabinet($input);
        $cabinet->school()
            ->associate(Auth::user()->currentSchool());
        $cabinet->save();

        return redirect()->route('cabinets.index')
            ->with('success','Cabinet created successfully');
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
        $cabinet = Cabinet::find($id);

        return view('cabinets.edit',compact('cabinet'));
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
            'number' => 'required',
        ]);

        $input = $request->all();

        $cabinet = Cabinet::find($id);
        $cabinet->update($input);


        return redirect()->route('cabinets.index')
            ->with('success','Cabinet updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cabinet::find($id)->delete();
        return redirect()->route('cabinets.index')
            ->with('success','Cabinet deleted successfully');
    }
}

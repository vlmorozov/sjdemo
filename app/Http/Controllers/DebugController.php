<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DebugController extends Controller
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
        $user = User::with('schools')->where('id', 7)->firstOrFail();

        dd($user);
    }
    
    public function any()
    {
        
    }
}

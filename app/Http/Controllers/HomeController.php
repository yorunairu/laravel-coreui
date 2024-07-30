<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
     * Redirect to admin dashboard.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectAdmin()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        return redirect()->route('dashboard');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('home');
    }
}
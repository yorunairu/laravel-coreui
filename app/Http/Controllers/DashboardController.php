<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Models\M_cusprin;
use App\Models\TrxTender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    public function index()
    {
        if (is_null($this->user) || !$this->user->can('dashboard.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view dashboard !');
        }
        $totalTenders = TrxTender::count();
        $pendingTenders = TrxTender::where('status_journey', '1')->count();
        $approvedTenders = TrxTender::where('status_journey', '2')->count();
        $rejectedTenders = TrxTender::where('status_journey', '3')->count();
        $recentTenders = TrxTender::orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard.index', compact('totalTenders', 'pendingTenders', 'approvedTenders', 'rejectedTenders', 'recentTenders'));
    }
}

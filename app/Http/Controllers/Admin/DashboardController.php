<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Real counts we can already calculate from the users table.
        // Money / course / enrollment stats stay at 0 for now and will
        // become real once we build the Orders and Courses modules.
        $stats = [
            'students'       => User::where('role', 'student')->count(),
            'instructors'    => User::where('role', 'instructor')->count(),
            'organizations'  => User::where('role', 'organization')->count(),
            'total_sale'     => 0,
            'platform_fee'   => 0,
            'enrollments'    => 0,
            'total_courses'  => 0,
            'online_courses' => 0,
            'offline_courses'=> 0,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

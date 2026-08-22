<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;

class DashboardController extends Controller
{
    public function index()
    {
        $instructorId = auth()->id();

        $courseIds = Course::where('instructor_id', $instructorId)->pluck('id');

        $stats = [
            'total_courses'     => $courseIds->count(),
            'published_courses' => Course::where('instructor_id', $instructorId)->where('status', 'published')->count(),
            'total_students'    => Enrollment::whereIn('course_id', $courseIds)->distinct('user_id')->count('user_id'),
            'total_earnings'    => 0, // becomes real once payments are built
        ];

        $recentCourses = Course::where('instructor_id', $instructorId)->latest()->take(5)->get();

        return view('instructor.dashboard', compact('stats', 'recentCourses'));
    }
}

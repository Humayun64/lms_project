<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Courses the student is enrolled in
        $enrolledCourses = $user->enrolledCourses()->with('category')->get();

        // Add a progress % to each enrolled course
        $completedIds = $user->completedLessonIds();
        foreach ($enrolledCourses as $course) {
            $course->load('sections.lessons');
            $total = $course->allLessons()->count();
            $done  = $course->allLessons()->whereIn('id', $completedIds)->count();
            $course->progress = $total ? round($done / $total * 100) : 0;
        }

        $enrolledIds = $enrolledCourses->pluck('id')->all();

        // Published courses the student can still enroll in
        $availableCourses = Course::where('status', 'published')
            ->whereNotIn('id', $enrolledIds)
            ->with('category')
            ->latest()
            ->get();

        return view('student.dashboard', compact('enrolledCourses', 'availableCourses'));
    }
}

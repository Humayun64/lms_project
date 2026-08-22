<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\OfflineRegistration;
use Illuminate\Http\Request;

class PublicCourseController extends Controller
{
    // Public course listing (visible to everyone)
    public function index(Request $request)
    {
        $query = Course::where('status', 'published')->with('category');

        if (in_array($request->type, ['online', 'offline'])) {
            $query->where('type', $request->type);
        }

        $courses = $query->latest()->paginate(12)->withQueryString();

        return view('public.courses.index', compact('courses'));
    }

    // Public course detail (visible to everyone)
    public function show(Course $course)
    {
        abort_if($course->status !== 'published', 404);

        $course->load('sections.lessons', 'category');
        $batches = $course->batches()->where('status', 'open')->get();

        return view('public.courses.show', compact('course', 'batches'));
    }

    // Handle offline course registration (public — no login required)
    public function register(Request $request, Course $course)
    {
        abort_if($course->type !== 'offline', 404);

        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255'],
            'phone'          => ['required', 'string', 'max:30'],
            'batch_id'       => ['nullable', 'exists:batches,id'],
            'payment_method' => ['nullable', 'in:in_person,online'],
        ]);

        OfflineRegistration::create([
            'course_id'      => $course->id,
            'batch_id'       => $data['batch_id'] ?? null,
            'user_id'        => auth()->id(),
            'name'           => $data['name'],
            'email'          => $data['email'],
            'phone'          => $data['phone'],
            'payment_method' => $data['payment_method'] ?? 'in_person',
            'status'         => 'pending',
        ]);

        return back()->with('registered', 'Thank you! Your registration has been received. Our team will contact you soon.');
    }
}

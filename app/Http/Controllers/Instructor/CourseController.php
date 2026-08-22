<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('instructor_id', auth()->id())
            ->with('category')->latest()->paginate(10);

        return view('instructor.courses.index', compact('courses'));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        return view('instructor.courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateCourse($request);

        $data['instructor_id'] = auth()->id();
        $data['slug']          = !empty($data['slug']) ? $data['slug'] : Str::slug($data['title']) . '-' . Str::random(5);
        $data['certificate']   = $request->boolean('certificate');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        Course::create($data);

        return redirect()->route('instructor.courses.index')->with('success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        $this->authorizeCourse($course);
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        return view('instructor.courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorizeCourse($course);

        $data = $this->validateCourse($request, $course->id);
        $data['slug']        = !empty($data['slug']) ? $data['slug'] : Str::slug($data['title']);
        $data['certificate'] = $request->boolean('certificate');

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) Storage::disk('public')->delete($course->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        $course->update($data);

        return redirect()->route('instructor.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $this->authorizeCourse($course);

        if ($course->thumbnail) Storage::disk('public')->delete($course->thumbnail);
        $course->delete();

        return redirect()->route('instructor.courses.index')->with('success', 'Course deleted.');
    }

    // Make sure the course belongs to the logged-in instructor
    private function authorizeCourse(Course $course): void
    {
        abort_if($course->instructor_id !== auth()->id(), 403);
    }

    private function validateCourse(Request $request, $ignoreId = null)
    {
        return $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'slug'              => ['nullable', 'string', 'max:255', 'unique:courses,slug' . ($ignoreId ? ",{$ignoreId}" : '')],
            'category_id'       => ['nullable', 'exists:categories,id'],
            'type'              => ['required', 'in:online,offline'],
            'level'             => ['nullable', 'in:beginner,intermediate,advanced'],
            'language'          => ['nullable', 'string', 'max:100'],
            'duration'          => ['nullable', 'string', 'max:100'],
            'thumbnail'         => ['nullable', 'image', 'max:2048'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description'       => ['nullable', 'string'],
            'outcome'           => ['nullable', 'string'],
            'final_project'     => ['nullable', 'string'],
            'price'             => ['nullable', 'numeric', 'min:0'],
            'sale_price'        => ['nullable', 'numeric', 'min:0'],
            'status'            => ['required', 'in:draft,published'],
        ]);
    }
}

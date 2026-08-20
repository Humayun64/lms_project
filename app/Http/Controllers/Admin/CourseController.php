<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    // List all courses
    public function index()
    {
        $courses = Course::with('category')->latest()->paginate(10);
        return view('admin.courses.index', compact('courses'));
    }

    // Show the "add course" form
    public function create()
    {
        $categories  = Category::where('status', 'active')->orderBy('name')->get();
        $instructors = User::where('role', 'instructor')->orderBy('name')->get();
        return view('admin.courses.create', compact('categories', 'instructors'));
    }

    // Save a new course
    public function store(Request $request)
    {
        $data = $this->validateCourse($request);

        $data['slug']        = !empty($data['slug']) ? $data['slug'] : Str::slug($data['title']) . '-' . Str::random(5);
        $data['certificate'] = $request->boolean('certificate');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        Course::create($data);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    // Show the "edit course" form
    public function edit(Course $course)
    {
        $categories  = Category::where('status', 'active')->orderBy('name')->get();
        $instructors = User::where('role', 'instructor')->orderBy('name')->get();
        return view('admin.courses.edit', compact('course', 'categories', 'instructors'));
    }

    // Save changes
    public function update(Request $request, Course $course)
    {
        $data = $this->validateCourse($request, $course->id);

        $data['slug']        = !empty($data['slug']) ? $data['slug'] : Str::slug($data['title']);
        $data['certificate'] = $request->boolean('certificate');

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        $course->update($data);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    // Delete a course
    public function destroy(Course $course)
    {
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }
        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    // Shared validation rules
    private function validateCourse(Request $request, $ignoreId = null)
    {
        return $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'slug'              => ['nullable', 'string', 'max:255', 'unique:courses,slug' . ($ignoreId ? ",{$ignoreId}" : '')],
            'category_id'       => ['nullable', 'exists:categories,id'],
            'instructor_id'     => ['nullable', 'exists:users,id'],
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
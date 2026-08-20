<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Section;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CurriculumController extends Controller
{
    // Show the curriculum page for a course
    public function index(Course $course)
    {
        $course->load('sections.lessons');
        return view('admin.courses.curriculum', compact('course'));
    }

    /* ---------------- SECTIONS ---------------- */

    public function storeSection(Request $request, Course $course)
    {
        $request->validate(['title' => ['required', 'string', 'max:255']]);

        $course->sections()->create([
            'title' => $request->title,
            'order' => $course->sections()->max('order') + 1,
        ]);

        return back()->with('success', 'Section added.');
    }

    public function updateSection(Request $request, Section $section)
    {
        $request->validate(['title' => ['required', 'string', 'max:255']]);
        $section->update(['title' => $request->title]);

        return back()->with('success', 'Section updated.');
    }

    public function destroySection(Section $section)
    {
        $section->delete(); // lessons cascade-delete with it
        return back()->with('success', 'Section deleted.');
    }

    /* ---------------- LESSONS ---------------- */

    public function storeLesson(Request $request, Section $section)
    {
        $data = $this->validateLesson($request);
        $data = $this->handleFiles($request, $data);

        $data['is_preview'] = $request->boolean('is_preview');
        $data['order']      = $section->lessons()->max('order') + 1;

        $section->lessons()->create($data);

        return back()->with('success', 'Lesson added.');
    }

    public function updateLesson(Request $request, Lesson $lesson)
    {
        $data = $this->validateLesson($request);
        $data = $this->handleFiles($request, $data, $lesson);

        $data['is_preview'] = $request->boolean('is_preview');

        $lesson->update($data);

        return back()->with('success', 'Lesson updated.');
    }

    public function destroyLesson(Lesson $lesson)
    {
        if ($lesson->video_file) Storage::disk('public')->delete($lesson->video_file);
        if ($lesson->file_path)  Storage::disk('public')->delete($lesson->file_path);

        $lesson->delete();
        return back()->with('success', 'Lesson deleted.');
    }

    /* ---------------- helpers ---------------- */

    private function validateLesson(Request $request)
    {
        return $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'type'         => ['required', 'in:video,text,pdf,quiz'],
            'video_source' => ['nullable', 'in:link,upload'],
            'video_url'    => ['nullable', 'string', 'max:500'],
            'video_file'   => ['nullable', 'file', 'mimes:mp4,mov,avi,webm', 'max:51200'],
            'content'      => ['nullable', 'string'],
            'file'         => ['nullable', 'file', 'mimes:pdf,doc,docx,zip', 'max:10240'],
            'duration'     => ['nullable', 'string', 'max:50'],
        ]);
    }

    private function handleFiles(Request $request, array $data, Lesson $lesson = null)
    {
        // Video upload
        if ($request->hasFile('video_file')) {
            if ($lesson && $lesson->video_file) Storage::disk('public')->delete($lesson->video_file);
            $data['video_file'] = $request->file('video_file')->store('lessons/videos', 'public');
        }

        // PDF / file upload
        if ($request->hasFile('file')) {
            if ($lesson && $lesson->file_path) Storage::disk('public')->delete($lesson->file_path);
            $data['file_path'] = $request->file('file')->store('lessons/files', 'public');
        }

        // Clean up fields that don't apply to the chosen type
        if ($data['type'] !== 'video') {
            $data['video_source'] = null;
            $data['video_url']    = null;
        }

        unset($data['file']); // 'file' is the form input name, not a column

        return $data;
    }
}

<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Certificate;
use App\Models\CertificateSetting;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    // Show (and issue if needed) the certificate for a completed online course
    public function show(Course $course)
    {
        $user = auth()->user();

        if (!$user->isEnrolledIn($course->id)) {
            return redirect()->route('student.dashboard')->with('error', 'You are not enrolled in this course.');
        }

        if ($course->type !== 'online' || !$course->certificate) {
            return redirect()->route('student.dashboard')->with('error', 'This course does not offer an online certificate.');
        }

        // Confirm the course is fully complete
        $course->load('sections.lessons');
        $all = $course->allLessons();
        $completed = $user->completedLessonIds();
        $allDone = $all->isNotEmpty() && $all->pluck('id')->every(fn ($id) => in_array($id, $completed));

        if (!$allDone) {
            return redirect()->route('student.learn', $course)->with('error', 'Finish all lessons to unlock your certificate.');
        }

        // Issue if not already
        $certificate = Certificate::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            [
                'certificate_number' => 'KA-' . now()->year . '-' . strtoupper(Str::random(6)),
                'issued_at'          => now(),
            ]
        );

        $settings = CertificateSetting::current();

        return view('certificate.show', compact('certificate', 'course', 'user', 'settings'));
    }
}

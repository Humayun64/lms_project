<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use Illuminate\Http\Request;

class LearnController extends Controller
{
    // Enroll the student in a course (free for now — payment hooks in later)
    public function enroll(Course $course)
    {
        Enrollment::firstOrCreate(
            ['user_id' => auth()->id(), 'course_id' => $course->id],
            ['enrolled_at' => now()]
        );

        return redirect()->route('student.learn', $course)->with('success', 'You are enrolled! Start learning.');
    }

    // The course player
    public function learn(Course $course, Lesson $lesson = null)
    {
        $user = auth()->user();

        if (!$user->isEnrolledIn($course->id)) {
            return redirect()->route('student.dashboard')->with('error', 'Please enroll in this course first.');
        }

        $course->load('sections.lessons');
        $allLessons = $course->allLessons()->values();

        if ($allLessons->isEmpty()) {
            return view('student.learn', [
                'course' => $course, 'lesson' => null, 'allLessons' => $allLessons,
                'completedIds' => [], 'progress' => 0, 'prev' => null, 'next' => null,
            ]);
        }

        // Default to the first lesson if none specified
        if (!$lesson) {
            $lesson = $allLessons->first();
        }

        // Make sure the lesson belongs to this course
        if (!$allLessons->contains('id', $lesson->id)) {
            abort(404);
        }

        $completedIds = $user->completedLessonIds();
        $total = $allLessons->count();
        $done  = $allLessons->whereIn('id', $completedIds)->count();
        $progress = $total ? round($done / $total * 100) : 0;

        // Prev / next lesson for navigation
        $index = $allLessons->search(fn ($l) => $l->id === $lesson->id);
        $prev  = $index > 0 ? $allLessons[$index - 1] : null;
        $next  = $index < $total - 1 ? $allLessons[$index + 1] : null;

        // Load quiz questions if this is a quiz lesson
        if ($lesson->type === 'quiz') {
            $lesson->load('questions.options');
        }

        return view('student.learn', compact('course', 'lesson', 'allLessons', 'completedIds', 'progress', 'prev', 'next'));
    }

    // Mark a lesson complete
    public function complete(Request $request, Lesson $lesson)
    {
        LessonCompletion::firstOrCreate(
            ['user_id' => auth()->id(), 'lesson_id' => $lesson->id],
            ['completed_at' => now()]
        );

        // Go to the next lesson if there is one
        $course = $lesson->section->course;
        $course->load('sections.lessons');
        $all = $course->allLessons()->values();
        $index = $all->search(fn ($l) => $l->id === $lesson->id);
        $next = $index < $all->count() - 1 ? $all[$index + 1] : null;

        if ($next) {
            return redirect()->route('student.learn.lesson', [$course, $next])->with('success', 'Lesson completed!');
        }

        return redirect()->route('student.learn', $course)->with('success', 'Course complete! 🎉');
    }

    // Grade a quiz submission
    public function submitQuiz(Request $request, Lesson $lesson)
    {
        $lesson->load('questions.options');
        $questions = $lesson->questions;
        $total = $questions->count();
        $correct = 0;

        foreach ($questions as $q) {
            $chosen = $request->input('answers.' . $q->id);
            $correctOption = $q->options->firstWhere('is_correct', true);
            if ($correctOption && (string) $chosen === (string) $correctOption->id) {
                $correct++;
            }
        }

        $score    = $total ? (int) round($correct / $total * 100) : 0;
        $passMark = $lesson->pass_mark ?? 0;
        $passed   = $score >= $passMark;

        if ($passed) {
            LessonCompletion::firstOrCreate(
                ['user_id' => auth()->id(), 'lesson_id' => $lesson->id],
                ['completed_at' => now()]
            );
        }

        $course = $lesson->section->course;

        return redirect()
            ->route('student.learn.lesson', [$course, $lesson])
            ->with('quiz_result', [
                'score' => $score, 'passed' => $passed, 'correct' => $correct, 'total' => $total, 'pass_mark' => $passMark,
            ]);
    }
}

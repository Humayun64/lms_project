<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    // Show the quiz builder for a quiz lesson
    public function index(Course $course, Lesson $lesson)
    {
        $lesson->load('questions.options');
        return view('admin.courses.quiz', compact('course', 'lesson'));
    }

    // Save the pass mark for this quiz
    public function updatePassMark(Request $request, Lesson $lesson)
    {
        $request->validate([
            'pass_mark' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $lesson->update(['pass_mark' => $request->pass_mark]);

        return back()->with('success', 'Pass mark saved.');
    }

    // Add a new question with its options
    public function storeQuestion(Request $request, Lesson $lesson)
    {
        $request->validate([
            'question'   => ['required', 'string'],
            'options'    => ['required', 'array', 'min:2'],
            'options.*'  => ['nullable', 'string', 'max:255'],
            'correct'    => ['required'],
        ]);

        // Keep only filled-in options, preserving their index
        $filled = [];
        foreach ($request->options as $i => $text) {
            if (trim((string) $text) !== '') {
                $filled[$i] = trim($text);
            }
        }

        if (count($filled) < 2) {
            return back()->withInput()->withErrors(['options' => 'Please provide at least 2 options.']);
        }

        if (!array_key_exists($request->correct, $filled)) {
            return back()->withInput()->withErrors(['correct' => 'Please mark one of the filled options as correct.']);
        }

        $question = $lesson->questions()->create([
            'question' => $request->question,
            'order'    => $lesson->questions()->max('order') + 1,
        ]);

        foreach ($filled as $i => $text) {
            $question->options()->create([
                'option_text' => $text,
                'is_correct'  => ((string) $i === (string) $request->correct),
            ]);
        }

        return back()->with('success', 'Question added.');
    }

    // Delete a question
    public function destroyQuestion(QuizQuestion $question)
    {
        $question->delete(); // options cascade-delete
        return back()->with('success', 'Question deleted.');
    }
}

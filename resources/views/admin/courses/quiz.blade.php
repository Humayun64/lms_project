@extends('admin.layouts.app')

@section('title', 'Quiz Builder')

@section('content')

    @php
        $input = 'w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500';
    @endphp

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.curriculum.index', $course) }}"
           class="w-9 h-9 flex items-center justify-center rounded-lg bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-slate-500 hover:text-slate-800">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-white">Quiz: {{ $lesson->title }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $course->title }}</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="my-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Pass mark --}}
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-4 my-5">
        <form method="POST" action="{{ route('admin.quiz.passmark', $lesson) }}" class="flex items-end gap-3">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Pass mark (%)</label>
                <input type="number" name="pass_mark" value="{{ $lesson->pass_mark }}" min="0" max="100"
                       placeholder="e.g. 60" class="{{ $input }} w-40">
            </div>
            <button type="submit" class="bg-slate-700 text-white px-4 py-2.5 rounded-lg hover:bg-slate-800 transition text-sm font-medium">Save</button>
            <p class="text-xs text-slate-400 ml-2 mb-2">Minimum score a student needs to pass this quiz.</p>
        </form>
    </div>

    {{-- Existing questions --}}
    @forelse ($lesson->questions as $index => $question)
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-5 mb-4">
            <div class="flex items-start justify-between">
                <div class="font-medium text-slate-800 dark:text-white mb-3">
                    <span class="text-indigo-500 mr-1">Q{{ $index + 1 }}.</span> {{ $question->question }}
                </div>
                <form method="POST" action="{{ route('admin.quiz.questions.destroy', $question) }}"
                      onsubmit="return confirm('Delete this question?');">
                    @csrf @method('DELETE')
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100" title="Delete question">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </form>
            </div>
            <div class="space-y-2">
                @foreach ($question->options as $option)
                    <div class="flex items-center gap-2 text-sm px-3 py-2 rounded-lg
                                {{ $option->is_correct ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-gray-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300' }}">
                        @if ($option->is_correct)
                            <i class="fa-solid fa-circle-check"></i>
                        @else
                            <i class="fa-regular fa-circle text-slate-400"></i>
                        @endif
                        {{ $option->option_text }}
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 px-6 py-8 text-center text-slate-400 mb-4">
            <i class="fa-solid fa-circle-question text-3xl mb-3 block"></i>
            No questions yet. Add your first question below.
        </div>
    @endforelse

    {{-- Add question --}}
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-5">
        <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4">Add a question</h2>
        <form method="POST" action="{{ route('admin.quiz.questions.store', $lesson) }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Question</label>
                <input type="text" name="question" value="{{ old('question') }}" class="{{ $input }}"
                       placeholder="e.g. What does SEO stand for?" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Options</label>
                <p class="text-xs text-slate-400 mb-2">Fill in 2–4 options. Select the radio button next to the correct one.</p>

                @for ($i = 0; $i < 4; $i++)
                    <div class="flex items-center gap-3 mb-2">
                        <input type="radio" name="correct" value="{{ $i }}" class="w-4 h-4" {{ old('correct') == $i ? 'checked' : '' }}>
                        <input type="text" name="options[{{ $i }}]" value="{{ old('options.' . $i) }}"
                               class="{{ $input }}" placeholder="Option {{ $i + 1 }}{{ $i < 2 ? ' (required)' : ' (optional)' }}">
                    </div>
                @endfor
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                <i class="fa-solid fa-plus mr-1"></i> Add Question
            </button>
        </form>
    </div>

@endsection

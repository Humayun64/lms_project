@extends('admin.layouts.app')

@section('title', 'Curriculum')

@section('content')

    @php
        $input = 'w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500';
    @endphp

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.courses.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-lg bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-slate-500 hover:text-slate-800">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-white">Curriculum</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $course->title }}</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="my-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Add section --}}
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-4 my-5">
        <form method="POST" action="{{ route('admin.curriculum.sections.store', $course) }}" class="flex gap-3">
            @csrf
            <input type="text" name="title" placeholder="New section title (e.g. SEO Basics)" class="{{ $input }}" required>
            <button type="submit" class="whitespace-nowrap bg-indigo-600 text-white px-4 py-2.5 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                <i class="fa-solid fa-plus mr-1"></i> Add Section
            </button>
        </form>
    </div>

    {{-- Sections + lessons --}}
    @forelse ($course->sections as $section)
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 mb-4 overflow-hidden">

            {{-- Section header --}}
            <div class="flex items-center justify-between px-5 py-3 bg-gray-50 dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
                <div class="flex items-center gap-2 font-medium text-slate-800 dark:text-white">
                    <i class="fa-solid fa-folder text-indigo-500"></i>
                    {{ $section->title }}
                    <span class="text-xs text-slate-400 font-normal">({{ $section->lessons->count() }} lessons)</span>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="document.getElementById('lesson-form-{{ $section->id }}').classList.toggle('hidden')"
                            class="text-xs bg-indigo-50 text-indigo-600 px-3 py-1.5 rounded-lg hover:bg-indigo-100">
                        <i class="fa-solid fa-plus mr-1"></i> Add Lesson
                    </button>
                    <form method="POST" action="{{ route('admin.curriculum.sections.destroy', $section) }}"
                          onsubmit="return confirm('Delete this section and all its lessons?');">
                        @csrf @method('DELETE')
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100" title="Delete section">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Lessons list --}}
            <div class="divide-y divide-gray-100 dark:divide-slate-800">
                @forelse ($section->lessons as $lesson)
                    <div class="flex items-center justify-between px-5 py-3">
                        <div class="flex items-center gap-3 text-sm">
                            @php
                                $icon = match($lesson->type) {
                                    'video' => 'fa-play',
                                    'text'  => 'fa-align-left',
                                    'pdf'   => 'fa-file-pdf',
                                    'quiz'  => 'fa-circle-question',
                                    default => 'fa-file',
                                };
                            @endphp
                            <span class="w-7 h-7 flex items-center justify-center rounded-md bg-gray-100 dark:bg-slate-800 text-slate-500">
                                <i class="fa-solid {{ $icon }} text-xs"></i>
                            </span>
                            <span class="text-slate-800 dark:text-white">{{ $lesson->title }}</span>
                            <span class="text-xs text-slate-400 capitalize">{{ $lesson->type }}</span>
                            @if ($lesson->is_preview)
                                <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Free preview</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            {{-- Manage questions (only for quiz lessons) --}}
                            @if ($lesson->type === 'quiz')
                                <a href="{{ route('admin.quiz.index', [$course, $lesson]) }}"
                                   class="inline-flex items-center gap-1 px-3 h-8 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition text-xs font-medium" title="Manage questions">
                                    <i class="fa-solid fa-circle-question text-xs"></i> Manage Questions
                                </a>
                            @endif
                            <form method="POST" action="{{ route('admin.curriculum.lessons.destroy', $lesson) }}"
                                  onsubmit="return confirm('Delete this lesson?');">
                                @csrf @method('DELETE')
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100" title="Delete lesson">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-4 text-sm text-slate-400">No lessons yet in this section.</div>
                @endforelse
            </div>

            {{-- Add lesson form --}}
            <div id="lesson-form-{{ $section->id }}" class="hidden px-5 py-4 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50">
                <form method="POST" action="{{ route('admin.curriculum.lessons.store', $section) }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <input type="text" name="title" placeholder="Lesson title" class="{{ $input }}" required>
                        <select name="type" class="{{ $input }} lesson-type" onchange="toggleLessonFields(this)">
                            <option value="video">Video</option>
                            <option value="text">Text / Article</option>
                            <option value="pdf">PDF / File</option>
                            <option value="quiz">Quiz</option>
                        </select>
                    </div>

                    <div class="field-video space-y-3">
                        <select name="video_source" class="{{ $input }}">
                            <option value="link">Video link (YouTube / Vimeo) — recommended</option>
                            <option value="upload">Upload video file</option>
                        </select>
                        <input type="text" name="video_url" placeholder="Paste video link here" class="{{ $input }}">
                        <input type="file" name="video_file" accept="video/*" class="{{ $input }}">
                        <p class="text-xs text-slate-400">Use a link for most videos. Upload only for small files (max 50MB).</p>
                    </div>

                    <div class="field-text hidden">
                        <textarea name="content" rows="4" placeholder="Write the lesson content here" class="{{ $input }}"></textarea>
                    </div>

                    <div class="field-pdf hidden">
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.zip" class="{{ $input }}">
                        <p class="text-xs text-slate-400 mt-1">PDF, DOC, or ZIP. Max 10MB.</p>
                    </div>

                    <div class="field-quiz hidden">
                        <p class="text-sm bg-amber-50 border border-amber-200 text-amber-700 px-3 py-2 rounded-lg">
                            Save the lesson, then click "Manage Questions" on it to add quiz questions.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <input type="text" name="duration" placeholder="Duration (e.g. 12:30)" class="{{ $input }}">
                        <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                            <input type="checkbox" name="is_preview" value="1" class="w-4 h-4">
                            Free preview (watchable before buying)
                        </label>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">Save Lesson</button>
                        <button type="button" onclick="document.getElementById('lesson-form-{{ $section->id }}').classList.add('hidden')"
                                class="px-4 py-2 rounded-lg border border-gray-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    @empty
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 px-6 py-12 text-center text-slate-400">
            <i class="fa-solid fa-list-check text-3xl mb-3 block"></i>
            No sections yet. Add your first section above to start building the curriculum.
        </div>
    @endforelse

    <script>
        function toggleLessonFields(select) {
            const form = select.closest('form');
            const type = select.value;
            form.querySelector('.field-video').classList.toggle('hidden', type !== 'video');
            form.querySelector('.field-text').classList.toggle('hidden', type !== 'text');
            form.querySelector('.field-pdf').classList.toggle('hidden', type !== 'pdf');
            form.querySelector('.field-quiz').classList.toggle('hidden', type !== 'quiz');
        }
    </script>

@endsection

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} - Player</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-slate-100">

<div class="flex min-h-screen">

    {{-- ============ Curriculum sidebar ============ --}}
    <aside class="w-80 bg-white border-r border-gray-200 flex flex-col">
        <div class="p-4 border-b border-gray-200">
            <a href="{{ route('student.dashboard') }}" class="text-sm text-slate-500 hover:text-indigo-600">
                <i class="fa-solid fa-arrow-left mr-1"></i> My Courses
            </a>
            <h1 class="font-semibold text-slate-800 mt-2">{{ $course->title }}</h1>
            {{-- Progress --}}
            <div class="mt-3">
                <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                    <span>Progress</span><span>{{ $progress }}%</span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full">
                    <div class="h-2 bg-indigo-600 rounded-full" style="width: {{ $progress }}%"></div>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto">
            @foreach ($course->sections as $section)
                <div class="px-4 py-2 bg-gray-50 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    {{ $section->title }}
                </div>
                @foreach ($section->lessons as $l)
                    @php $isDone = in_array($l->id, $completedIds); @endphp
                    <a href="{{ route('student.learn.lesson', [$course, $l]) }}"
                       class="flex items-center gap-3 px-4 py-3 text-sm border-b border-gray-100 hover:bg-indigo-50
                              {{ $lesson && $l->id === $lesson->id ? 'bg-indigo-50 border-l-2 border-l-indigo-600' : '' }}">
                        <span class="flex-shrink-0">
                            @if ($isDone)
                                <i class="fa-solid fa-circle-check text-green-500"></i>
                            @else
                                @php
                                    $ic = match($l->type) {
                                        'video' => 'fa-play', 'text' => 'fa-align-left',
                                        'pdf' => 'fa-file-pdf', 'quiz' => 'fa-circle-question', default => 'fa-file',
                                    };
                                @endphp
                                <i class="fa-solid {{ $ic }} text-slate-400"></i>
                            @endif
                        </span>
                        <span class="flex-1 text-slate-700">{{ $l->title }}</span>
                        @if ($l->duration)<span class="text-xs text-slate-400">{{ $l->duration }}</span>@endif
                    </a>
                @endforeach
            @endforeach
        </div>
    </aside>

    {{-- ============ Lesson content ============ --}}
    <main class="flex-1 p-6 lg:p-10 max-w-4xl">

        @if (!$lesson)
            <div class="bg-white rounded-xl border border-gray-200 px-6 py-16 text-center text-slate-400">
                <i class="fa-solid fa-circle-info text-3xl mb-3 block"></i>
                This course has no lessons yet.
            </div>
        @else
            <div class="mb-2 text-sm text-indigo-600 capitalize">{{ $lesson->type }} lesson</div>
            <h2 class="text-2xl font-semibold text-slate-800 mb-6">{{ $lesson->title }}</h2>

            {{-- ===== VIDEO ===== --}}
            @if ($lesson->type === 'video')
                <div class="bg-black rounded-xl overflow-hidden aspect-video mb-6">
                    @if ($lesson->video_source === 'upload' && $lesson->video_file)
                        <video controls class="w-full h-full">
                            <source src="{{ asset('storage/' . $lesson->video_file) }}">
                        </video>
                    @elseif ($lesson->embed_url)
                        <iframe src="{{ $lesson->embed_url }}" class="w-full h-full" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">No video provided.</div>
                    @endif
                </div>

            {{-- ===== TEXT ===== --}}
            @elseif ($lesson->type === 'text')
                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $lesson->content }}
                </div>

            {{-- ===== PDF / FILE ===== --}}
            @elseif ($lesson->type === 'pdf')
                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
                    @if ($lesson->file_path)
                        <a href="{{ asset('storage/' . $lesson->file_path) }}" target="_blank"
                           class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2.5 rounded-lg hover:bg-indigo-700 text-sm">
                            <i class="fa-solid fa-download"></i> Open / Download file
                        </a>
                    @else
                        <p class="text-slate-400 text-sm">No file attached.</p>
                    @endif
                </div>

            {{-- ===== QUIZ ===== --}}
            @elseif ($lesson->type === 'quiz')
                @if (session('quiz_result'))
                    @php $r = session('quiz_result'); @endphp
                    <div class="rounded-xl border p-5 mb-6 {{ $r['passed'] ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700' }}">
                        <div class="font-semibold text-lg mb-1">
                            {{ $r['passed'] ? 'Passed! 🎉' : 'Not passed yet' }}
                        </div>
                        You scored {{ $r['score'] }}% ({{ $r['correct'] }}/{{ $r['total'] }} correct). Pass mark: {{ $r['pass_mark'] }}%.
                        @if (!$r['passed']) <div class="mt-1 text-sm">You can review and try again below.</div> @endif
                    </div>
                @endif

                <form method="POST" action="{{ route('student.quiz.submit', $lesson) }}" class="space-y-5 mb-6">
                    @csrf
                    @forelse ($lesson->questions as $i => $q)
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            <div class="font-medium text-slate-800 mb-3">Q{{ $i + 1 }}. {{ $q->question }}</div>
                            <div class="space-y-2">
                                @foreach ($q->options as $opt)
                                    <label class="flex items-center gap-3 px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer text-sm">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt->id }}" class="w-4 h-4" required>
                                        {{ $opt->option_text }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400 text-sm">This quiz has no questions yet.</p>
                    @endforelse

                    @if ($lesson->questions->isNotEmpty())
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                            Submit Quiz
                        </button>
                    @endif
                </form>
            @endif

            {{-- ===== Actions: mark complete + navigation ===== --}}
            <div class="flex items-center justify-between border-t border-gray-200 pt-5">
                <div>
                    @if ($prev)
                        <a href="{{ route('student.learn.lesson', [$course, $prev]) }}" class="text-sm text-slate-600 hover:text-indigo-600">
                            <i class="fa-solid fa-arrow-left mr-1"></i> Previous
                        </a>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    @if ($lesson->type !== 'quiz')
                        <form method="POST" action="{{ route('student.lesson.complete', $lesson) }}">
                            @csrf
                            <button type="submit"
                                    class="{{ in_array($lesson->id, $completedIds) ? 'bg-green-100 text-green-700' : 'bg-indigo-600 text-white hover:bg-indigo-700' }} px-5 py-2.5 rounded-lg transition text-sm font-medium">
                                @if (in_array($lesson->id, $completedIds))
                                    <i class="fa-solid fa-check mr-1"></i> Completed
                                @else
                                    Mark as Complete
                                @endif
                            </button>
                        </form>
                    @endif

                    @if ($next)
                        <a href="{{ route('student.learn.lesson', [$course, $next]) }}"
                           class="text-sm text-slate-600 hover:text-indigo-600">
                            Next <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </main>
</div>

</body>
</html>

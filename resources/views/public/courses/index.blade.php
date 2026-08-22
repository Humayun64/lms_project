@extends('layouts.public')

@section('title', 'All Courses')

@section('content')

    {{-- Hero --}}
    <div class="bg-indigo-900 text-white">
        <div class="max-w-6xl mx-auto px-4 py-14 text-center">
            <h1 class="text-3xl font-semibold mb-2">Explore Our Courses</h1>
            <p class="text-indigo-200">Online and in-person training to build real skills.</p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-10">

        {{-- Filter --}}
        <div class="flex items-center gap-2 mb-8 text-sm">
            <a href="{{ route('courses.index') }}"
               class="px-4 py-2 rounded-full {{ !request('type') ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200 text-slate-600' }}">All</a>
            <a href="{{ route('courses.index', ['type' => 'online']) }}"
               class="px-4 py-2 rounded-full {{ request('type') === 'online' ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200 text-slate-600' }}">Online</a>
            <a href="{{ route('courses.index', ['type' => 'offline']) }}"
               class="px-4 py-2 rounded-full {{ request('type') === 'offline' ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200 text-slate-600' }}">Offline</a>
        </div>

        @if ($courses->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 px-6 py-16 text-center text-slate-400">
                No courses published yet.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($courses as $course)
                    <a href="{{ route('courses.show', $course) }}"
                       class="bg-white rounded-xl border border-gray-200 overflow-hidden flex flex-col hover:shadow-md transition">
                        <div class="h-40 bg-gray-100">
                            @if ($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" class="w-full h-full object-cover" alt="">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fa-solid fa-image text-3xl"></i></div>
                            @endif
                        </div>
                        <div class="p-4 flex flex-col flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs text-indigo-600">{{ $course->category->name ?? '' }}</span>
                                @if ($course->type === 'online')
                                    <span class="text-[10px] bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">Online</span>
                                @else
                                    <span class="text-[10px] bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full">Offline</span>
                                @endif
                            </div>
                            <h3 class="font-semibold text-slate-800 mb-1">{{ $course->title }}</h3>
                            <p class="text-xs text-slate-500 line-clamp-2 mb-3">{{ $course->short_description }}</p>
                            <div class="mt-auto flex items-center justify-between text-xs text-slate-400">
                                <span>{{ $course->duration ?: '' }}</span>
                                <span class="text-indigo-600 font-medium">View details →</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">{{ $courses->links() }}</div>
        @endif
    </div>

@endsection

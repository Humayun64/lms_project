@extends('instructor.layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-800 dark:text-white">Welcome, {{ auth()->user()->name }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Here's an overview of your teaching.</p>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm text-slate-500 dark:text-slate-400">My courses</span>
                <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="fa-solid fa-book"></i></div>
            </div>
            <div class="text-2xl font-semibold mt-3 text-slate-800 dark:text-white">{{ $stats['total_courses'] }}</div>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm text-slate-500 dark:text-slate-400">Published</span>
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-circle-check"></i></div>
            </div>
            <div class="text-2xl font-semibold mt-3 text-slate-800 dark:text-white">{{ $stats['published_courses'] }}</div>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm text-slate-500 dark:text-slate-400">Students</span>
                <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center"><i class="fa-solid fa-users"></i></div>
            </div>
            <div class="text-2xl font-semibold mt-3 text-slate-800 dark:text-white">{{ $stats['total_students'] }}</div>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm text-slate-500 dark:text-slate-400">Earnings</span>
                <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center"><i class="fa-solid fa-wallet"></i></div>
            </div>
            <div class="text-2xl font-semibold mt-3 text-slate-800 dark:text-white">${{ number_format($stats['total_earnings'], 2) }}</div>
            <div class="text-xs text-slate-400 mt-1">Available after payments are set up</div>
        </div>
    </div>

    {{-- Recent courses --}}
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200">Your recent courses</h2>
            <a href="{{ route('instructor.courses.index') }}" class="text-xs text-emerald-600 hover:underline">View all</a>
        </div>
        @if ($recentCourses->isEmpty())
            <div class="text-center text-slate-400 py-8 text-sm">
                <i class="fa-solid fa-book text-2xl mb-2 block"></i>
                You haven't created any courses yet.
                <div class="mt-3"><a href="{{ route('instructor.courses.create') }}" class="text-emerald-600 hover:underline">Create your first course</a></div>
            </div>
        @else
            <div class="space-y-2">
                @foreach ($recentCourses as $course)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-slate-800 last:border-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-8 rounded bg-gray-100 dark:bg-slate-800 overflow-hidden">
                                @if ($course->thumbnail)<img src="{{ asset('storage/' . $course->thumbnail) }}" class="w-full h-full object-cover">@endif
                            </div>
                            <span class="text-sm text-slate-800 dark:text-white">{{ $course->title }}</span>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full {{ $course->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">{{ ucfirst($course->status) }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection

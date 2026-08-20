@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- Welcome header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-800 dark:text-white">
            Welcome back, {{ auth()->user()->name }}
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Here's what's happening today, {{ now()->format('d M Y') }}
        </p>
    </div>

    {{-- Top stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-4">

        {{-- Total sale --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm text-slate-500 dark:text-slate-400">Total sale</span>
                <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
            </div>
            <div class="text-2xl font-semibold mt-3 text-slate-800 dark:text-white">
                ${{ number_format($stats['total_sale'], 2) }}
            </div>
            <div class="text-xs text-slate-400 mt-1">Updates when orders start</div>
        </div>

        {{-- Platform fee --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm text-slate-500 dark:text-slate-400">Platform fee</span>
                <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
            <div class="text-2xl font-semibold mt-3 text-slate-800 dark:text-white">
                ${{ number_format($stats['platform_fee'], 2) }}
            </div>
            <div class="text-xs text-slate-400 mt-1">Your revenue-share cut</div>
        </div>

        {{-- Enrollments --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm text-slate-500 dark:text-slate-400">Enrollments</span>
                <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-user-check"></i>
                </div>
            </div>
            <div class="text-2xl font-semibold mt-3 text-slate-800 dark:text-white">
                {{ number_format($stats['enrollments']) }}
            </div>
            <div class="text-xs text-slate-400 mt-1">Across all courses</div>
        </div>

        {{-- Courses --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm text-slate-500 dark:text-slate-400">Courses</span>
                <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fa-solid fa-book"></i>
                </div>
            </div>
            <div class="text-2xl font-semibold mt-3 text-slate-800 dark:text-white">
                {{ number_format($stats['total_courses']) }}
            </div>
            <div class="text-xs text-slate-400 mt-1">
                {{ $stats['online_courses'] }} online · {{ $stats['offline_courses'] }} offline
            </div>
        </div>
    </div>

    {{-- Revenue chart + people --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Revenue overview --}}
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200">Revenue overview</h2>
                <span class="text-xs text-slate-400">Last 7 months</span>
            </div>
            {{-- Placeholder chart (becomes real data once orders exist) --}}
            <svg viewBox="0 0 400 150" class="w-full h-36">
                <polyline points="0,120 66,95 132,105 198,60 264,72 330,35 400,45"
                          fill="none" stroke="#6366f1" stroke-width="2.5"/>
                <polygon points="0,120 66,95 132,105 198,60 264,72 330,35 400,45 400,150 0,150"
                         fill="#6366f1" opacity="0.10"/>
                <circle cx="330" cy="35" r="4" fill="#6366f1"/>
            </svg>
            <div class="flex justify-between text-xs text-slate-400 mt-2">
                <span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span><span>Jul</span><span>Aug</span>
            </div>
        </div>

        {{-- People --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-5">
            <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4">People</h2>
            <div class="space-y-4">

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-slate-800 dark:text-white">{{ $stats['students'] }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Students</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-slate-800 dark:text-white">{{ $stats['instructors'] }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Instructors</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-slate-800 dark:text-white">{{ $stats['organizations'] }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Organizations</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

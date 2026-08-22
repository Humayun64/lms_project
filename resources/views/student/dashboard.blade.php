@extends('student.layouts.app')

@section('title', 'My Learning')

@section('content')

    <h1 class="text-2xl font-semibold text-slate-800 mb-1">Welcome back, {{ auth()->user()->name }}</h1>
    <p class="text-sm text-slate-500 mb-8">Continue learning or explore new courses.</p>

    {{-- My Courses --}}
    <h2 class="text-lg font-semibold text-slate-800 mb-4">My Courses</h2>
    @if ($enrolledCourses->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 px-6 py-10 text-center text-slate-400 mb-10">
            <i class="fa-solid fa-book-open text-3xl mb-3 block"></i>
            You're not enrolled in any course yet. Pick one below to get started.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-10">
            @foreach ($enrolledCourses as $course)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden flex flex-col">
                    <div class="h-32 bg-gray-100">
                        @if ($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" class="w-full h-full object-cover" alt="">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fa-solid fa-image text-2xl"></i></div>
                        @endif
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <div class="text-xs text-indigo-600 mb-1">{{ $course->category->name ?? '' }}</div>
                        <h3 class="font-semibold text-slate-800 mb-3">{{ $course->title }}</h3>

                        <div class="mt-auto">
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                                <span>Progress</span><span>{{ $course->progress }}%</span>
                            </div>
                            <div class="w-full h-2 bg-gray-100 rounded-full mb-3">
                                <div class="h-2 bg-indigo-600 rounded-full" style="width: {{ $course->progress }}%"></div>
                            </div>

                            <a href="{{ route('student.learn', $course) }}"
                               class="block text-center bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                                {{ $course->progress > 0 ? 'Continue' : 'Start Learning' }}
                            </a>

                            {{-- Certificate button: online course, 100% complete --}}
                            @if ($course->progress == 100 && $course->type === 'online' && $course->certificate)
                                <a href="{{ route('student.certificate', $course) }}"
                                   class="block text-center mt-2 border border-green-600 text-green-700 py-2 rounded-lg hover:bg-green-50 transition text-sm font-medium">
                                    <i class="fa-solid fa-certificate mr-1"></i> View Certificate
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Available Courses --}}
    <h2 class="text-lg font-semibold text-slate-800 mb-4">Available Courses</h2>
    @if ($availableCourses->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 px-6 py-10 text-center text-slate-400">
            No other courses available right now.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($availableCourses as $course)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden flex flex-col">
                    <div class="h-32 bg-gray-100">
                        @if ($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" class="w-full h-full object-cover" alt="">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fa-solid fa-image text-2xl"></i></div>
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
                        <p class="text-xs text-slate-500 mb-4 line-clamp-2">{{ $course->short_description }}</p>

                        <form method="POST" action="{{ route('student.enroll', $course) }}" class="mt-auto">
                            @csrf
                            <button type="submit"
                                    class="w-full border border-indigo-600 text-indigo-600 py-2 rounded-lg hover:bg-indigo-50 transition text-sm font-medium">
                                Enroll {{ $course->price ? '· $' . number_format($course->price, 2) : '(Free for now)' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection

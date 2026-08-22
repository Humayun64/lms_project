@extends('instructor.layouts.app')

@section('title', 'My Courses')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-white">My Courses</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Create and manage your own courses</p>
        </div>
        <a href="{{ route('instructor.courses.create') }}"
           class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2.5 rounded-lg hover:bg-emerald-700 transition text-sm font-medium">
            <i class="fa-solid fa-plus"></i> Add Course
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-left">
                    <tr>
                        <th class="px-6 py-3 font-medium">Course</th>
                        <th class="px-6 py-3 font-medium">School</th>
                        <th class="px-6 py-3 font-medium">Type</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse ($courses as $course)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" class="w-14 h-10 object-cover rounded-md border border-gray-200 dark:border-slate-700">
                                    @else
                                        <div class="w-14 h-10 rounded-md bg-gray-100 dark:bg-slate-800 flex items-center justify-center text-slate-400"><i class="fa-solid fa-image text-xs"></i></div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-slate-800 dark:text-white">{{ $course->title }}</div>
                                        <div class="text-xs text-slate-400">{{ $course->duration ?: '—' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400">{{ $course->category->name ?? '—' }}</td>
                            <td class="px-6 py-4">
                                @if ($course->type === 'online')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700"><i class="fa-solid fa-wifi text-[10px]"></i> Online</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-full bg-orange-100 text-orange-700"><i class="fa-solid fa-location-dot text-[10px]"></i> Offline</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($course->status === 'published')
                                    <span class="inline-block px-2.5 py-1 text-xs rounded-full bg-green-100 text-green-700">Published</span>
                                @else
                                    <span class="inline-block px-2.5 py-1 text-xs rounded-full bg-gray-200 text-gray-600">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('courses.show', $course) }}" target="_blank"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100" title="View public page">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('instructor.courses.edit', $course) }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100" title="Edit">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('instructor.courses.destroy', $course) }}" onsubmit="return confirm('Delete this course?');">
                                        @csrf @method('DELETE')
                                        <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100" title="Delete"><i class="fa-solid fa-trash text-xs"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-book text-3xl mb-3 block"></i>
                                No courses yet. Click "Add Course" to create your first one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($courses->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800">{{ $courses->links() }}</div>
        @endif
    </div>

    <p class="text-xs text-slate-400 mt-4">Note: curriculum (lessons &amp; quizzes) management for your courses is coming in the next step.</p>

@endsection

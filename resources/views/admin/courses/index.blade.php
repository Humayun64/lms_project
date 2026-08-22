@extends('admin.layouts.app')

@section('title', 'Courses')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-white">Courses</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage your online and offline courses</p>
        </div>
        <a href="{{ route('admin.courses.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2.5 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
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
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt=""
                                             class="w-14 h-10 object-cover rounded-md border border-gray-200 dark:border-slate-700">
                                    @else
                                        <div class="w-14 h-10 rounded-md bg-gray-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                            <i class="fa-solid fa-image text-xs"></i>
                                        </div>
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
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700">
                                        <i class="fa-solid fa-wifi text-[10px]"></i> Online
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-full bg-orange-100 text-orange-700">
                                        <i class="fa-solid fa-location-dot text-[10px]"></i> Offline
                                    </span>
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
                                    {{-- View public page --}}
                                    <a href="{{ route('courses.show', $course) }}" target="_blank"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100 transition" title="View public page">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    {{-- Curriculum / Module list --}}
                                    <a href="{{ route('admin.curriculum.index', $course) }}"
                                       class="inline-flex items-center gap-1 px-3 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition text-xs font-medium" title="Curriculum / Modules">
                                        <i class="fa-solid fa-list-check text-xs"></i> {{ $course->type === 'offline' ? 'Modules' : 'Curriculum' }}
                                    </a>
                                    {{-- Batches (offline only) --}}
                                    @if ($course->type === 'offline')
                                        <a href="{{ route('admin.batches.index', $course) }}"
                                           class="inline-flex items-center gap-1 px-3 h-8 rounded-lg bg-orange-50 text-orange-600 hover:bg-orange-100 transition text-xs font-medium" title="Batches & registrations">
                                            <i class="fa-solid fa-calendar-days text-xs"></i> Batches
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.courses.edit', $course) }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition" title="Edit">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}"
                                          onsubmit="return confirm('Delete this course?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition" title="Delete">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
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
            <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800">
                {{ $courses->links() }}
            </div>
        @endif
    </div>

@endsection

@extends('admin.layouts.app')

@section('title', 'Certificates')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-white">Certificates</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Auto-issued when students complete online courses</p>
        </div>
        <a href="{{ route('admin.certificate-settings.edit') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2.5 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
            <i class="fa-solid fa-gear"></i> Certificate Settings
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-left">
                    <tr>
                        <th class="px-6 py-3 font-medium">Certificate ID</th>
                        <th class="px-6 py-3 font-medium">Student</th>
                        <th class="px-6 py-3 font-medium">Course</th>
                        <th class="px-6 py-3 font-medium">Issued</th>
                        <th class="px-6 py-3 font-medium text-right">Verify</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse ($certificates as $cert)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50">
                            <td class="px-6 py-4 font-mono text-xs text-slate-600 dark:text-slate-300">{{ $cert->certificate_number }}</td>
                            <td class="px-6 py-4 text-slate-800 dark:text-white">{{ $cert->user->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400">{{ $cert->course->title ?? '—' }}</td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400">{{ $cert->issued_at?->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ url('/verify/' . $cert->certificate_number) }}" target="_blank"
                                   class="text-indigo-600 hover:underline text-xs">Open <i class="fa-solid fa-arrow-up-right-from-square ml-0.5"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-certificate text-3xl mb-3 block"></i>
                                No certificates issued yet. They appear here when a student completes an online course.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($certificates->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800">
                {{ $certificates->links() }}
            </div>
        @endif
    </div>

@endsection

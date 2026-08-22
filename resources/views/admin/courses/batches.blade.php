@extends('admin.layouts.app')

@section('title', 'Offline Batches')

@section('content')

    @php
        $input = 'w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500';
    @endphp

    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.courses.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-lg bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-slate-500 hover:text-slate-800">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-white">Offline Management</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $course->title }}</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="my-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">{{ $errors->first() }}</div>
    @endif

    {{-- Payment option --}}
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-5 my-5">
        <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">How do students pay?</h2>
        <form method="POST" action="{{ route('admin.batches.payment', $course) }}" class="flex items-end gap-3">
            @csrf @method('PUT')
            <select name="offline_payment" class="{{ $input }} max-w-xs">
                <option value="in_person" {{ $course->offline_payment === 'in_person' ? 'selected' : '' }}>Pay in person only</option>
                <option value="online" {{ $course->offline_payment === 'online' ? 'selected' : '' }}>Pay online only</option>
                <option value="both" {{ $course->offline_payment === 'both' ? 'selected' : '' }}>Both (student chooses)</option>
            </select>
            <button class="bg-slate-700 text-white px-4 py-2.5 rounded-lg hover:bg-slate-800 text-sm font-medium">Save</button>
        </form>
    </div>

    {{-- Add batch --}}
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-5 mb-5">
        <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">Add a batch</h2>
        <form method="POST" action="{{ route('admin.batches.store', $course) }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
            @csrf
            <input type="text" name="name" placeholder="Batch name" class="{{ $input }}" required>
            <input type="date" name="start_date" class="{{ $input }}">
            <input type="text" name="schedule" placeholder="Schedule" class="{{ $input }}">
            <input type="text" name="venue" placeholder="Venue" class="{{ $input }}">
            <div class="flex gap-2">
                <input type="number" name="seats" placeholder="Seats" class="{{ $input }}">
                <button class="whitespace-nowrap bg-indigo-600 text-white px-4 rounded-lg hover:bg-indigo-700 text-sm font-medium">Add</button>
            </div>
        </form>
    </div>

    {{-- Batches list --}}
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 mb-6 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-200 dark:border-slate-800 font-semibold text-sm text-slate-700 dark:text-slate-200">Batches</div>
        @forelse ($course->batches as $batch)
            <div class="border-b border-gray-100 dark:border-slate-800">
                <div class="flex items-center justify-between px-5 py-3 text-sm">
                    <div>
                        <span class="font-medium text-slate-800 dark:text-white">{{ $batch->name }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full ml-1 {{ $batch->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">{{ ucfirst($batch->status) }}</span>
                        <span class="text-xs text-slate-400 ml-2">
                            {{ $batch->start_date?->format('d M Y') }}
                            @if ($batch->schedule) · {{ $batch->schedule }} @endif
                            @if ($batch->venue) · {{ $batch->venue }} @endif
                            @if ($batch->seats) · {{ $batch->seats }} seats @endif
                            · {{ $batch->registrations->count() }} registered
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="document.getElementById('edit-batch-{{ $batch->id }}').classList.toggle('hidden')"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100" title="Edit">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.batches.destroy', $batch) }}" onsubmit="return confirm('Delete this batch?');">
                            @csrf @method('DELETE')
                            <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100"><i class="fa-solid fa-trash text-xs"></i></button>
                        </form>
                    </div>
                </div>

                <div id="edit-batch-{{ $batch->id }}" class="hidden px-5 pb-4 bg-gray-50 dark:bg-slate-800/40">
                    <form method="POST" action="{{ route('admin.batches.update', $batch) }}" class="grid grid-cols-1 md:grid-cols-6 gap-3 pt-4">
                        @csrf @method('PUT')
                        <input type="text" name="name" value="{{ $batch->name }}" class="{{ $input }}" required>
                        <input type="date" name="start_date" value="{{ $batch->start_date?->format('Y-m-d') }}" class="{{ $input }}">
                        <input type="text" name="schedule" value="{{ $batch->schedule }}" placeholder="Schedule" class="{{ $input }}">
                        <input type="text" name="venue" value="{{ $batch->venue }}" placeholder="Venue" class="{{ $input }}">
                        <input type="number" name="seats" value="{{ $batch->seats }}" placeholder="Seats" class="{{ $input }}">
                        <div class="flex gap-2">
                            <select name="status" class="{{ $input }}">
                                <option value="open" {{ $batch->status === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ $batch->status === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            <button class="whitespace-nowrap bg-indigo-600 text-white px-4 rounded-lg hover:bg-indigo-700 text-sm font-medium">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="px-5 py-6 text-center text-slate-400 text-sm">No batches yet.</div>
        @endforelse
    </div>

    {{-- Registrations --}}
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-200 dark:border-slate-800 font-semibold text-sm text-slate-700 dark:text-slate-200">Registrations</div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-left">
                    <tr>
                        <th class="px-5 py-3 font-medium">Name</th>
                        <th class="px-5 py-3 font-medium">Contact</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium">Update</th>
                        <th class="px-5 py-3 font-medium text-right">Certificate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse ($registrations as $reg)
                        <tr>
                            <td class="px-5 py-3 text-slate-800 dark:text-white">{{ $reg->name }}</td>
                            <td class="px-5 py-3 text-slate-500 dark:text-slate-400">
                                <div>{{ $reg->email }}</div><div class="text-xs">{{ $reg->phone }}</div>
                            </td>
                            <td class="px-5 py-3">
                                @php
                                    $badge = match($reg->status) {
                                        'confirmed' => 'bg-blue-100 text-blue-700',
                                        'completed' => 'bg-green-100 text-green-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                        default     => 'bg-gray-200 text-gray-600',
                                    };
                                @endphp
                                <span class="text-xs px-2 py-1 rounded-full {{ $badge }} capitalize">{{ $reg->status }}</span>
                                @if ($reg->paid)<span class="ml-1 text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Paid</span>@endif
                            </td>
                            <td class="px-5 py-3">
                                <form method="POST" action="{{ route('admin.registrations.update', $reg) }}" class="flex items-center gap-2">
                                    @csrf @method('PUT')
                                    <select name="status" class="rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-2 py-1.5 text-xs">
                                        @foreach (['pending','confirmed','completed','cancelled'] as $st)
                                            <option value="{{ $st }}" {{ $reg->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                        @endforeach
                                    </select>
                                    <label class="flex items-center gap-1 text-xs text-slate-500">
                                        <input type="checkbox" name="paid" value="1" {{ $reg->paid ? 'checked' : '' }}> Paid
                                    </label>
                                    <button class="bg-slate-700 text-white px-3 py-1.5 rounded-lg text-xs">Save</button>
                                </form>
                            </td>
                            <td class="px-5 py-3 text-right">
                                @if ($reg->certificate)
                                    <a href="{{ route('admin.certificates.print', $reg->certificate) }}" target="_blank"
                                       class="inline-flex items-center gap-1 text-green-700 text-xs font-medium">
                                        <i class="fa-solid fa-circle-check"></i> Issued · Print
                                    </a>
                                @elseif ($reg->status === 'completed')
                                    <form method="POST" action="{{ route('admin.registrations.certificate', $reg) }}"
                                          onsubmit="return confirm('Issue a certificate to {{ $reg->name }}?');">
                                        @csrf
                                        <button class="inline-flex items-center gap-1 bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-700 text-xs font-medium">
                                            <i class="fa-solid fa-certificate"></i> Issue Certificate
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">Mark "completed" to issue</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">No registrations yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

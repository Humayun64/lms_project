@extends('admin.layouts.app')

@section('title', 'Users')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-white">Users</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage students, instructors, organizations, and admins</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2.5 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
            <i class="fa-solid fa-plus"></i> Add User
        </a>
    </div>

    {{-- Role tabs --}}
    <div class="flex flex-wrap items-center gap-2 mb-4 text-sm">
        @php
            $tabs = [
                ''             => ['All', $counts['all']],
                'student'      => ['Students', $counts['student']],
                'instructor'   => ['Instructors', $counts['instructor']],
                'organization' => ['Organizations', $counts['organization']],
                'admin'        => ['Admins', $counts['admin']],
            ];
        @endphp
        @foreach ($tabs as $key => [$label, $count])
            <a href="{{ route('admin.users.index', array_filter(['role' => $key])) }}"
               class="px-3.5 py-2 rounded-lg {{ ($role ?? '') === $key ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-slate-600 dark:text-slate-300' }}">
                {{ $label }} <span class="opacity-70">({{ $count }})</span>
            </a>
        @endforeach
    </div>

    {{-- Search --}}
    <form method="GET" class="mb-4 flex gap-2">
        @if ($role)<input type="hidden" name="role" value="{{ $role }}">@endif
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..."
               class="w-full max-w-sm rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <button class="bg-slate-700 text-white px-4 py-2.5 rounded-lg hover:bg-slate-800 text-sm">Search</button>
    </form>

    <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-left">
                    <tr>
                        <th class="px-6 py-3 font-medium">Name</th>
                        <th class="px-6 py-3 font-medium">Email</th>
                        <th class="px-6 py-3 font-medium">Role</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium">Joined</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-semibold">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <span class="font-medium text-slate-800 dark:text-white">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $rc = match($user->role) {
                                        'admin' => 'bg-purple-100 text-purple-700',
                                        'instructor' => 'bg-blue-100 text-blue-700',
                                        'organization' => 'bg-amber-100 text-amber-700',
                                        default => 'bg-slate-100 text-slate-600',
                                    };
                                @endphp
                                <span class="text-xs px-2.5 py-1 rounded-full {{ $rc }} capitalize">{{ $user->role }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($user->status === 'active')
                                    <span class="text-xs px-2.5 py-1 rounded-full bg-green-100 text-green-700">Active</span>
                                @else
                                    <span class="text-xs px-2.5 py-1 rounded-full bg-red-100 text-red-700">Blocked</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400">{{ $user->created_at?->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Block / unblock --}}
                                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                        @csrf @method('PUT')
                                        <button class="w-8 h-8 flex items-center justify-center rounded-lg {{ $user->status === 'active' ? 'bg-orange-50 text-orange-600 hover:bg-orange-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}"
                                                title="{{ $user->status === 'active' ? 'Block' : 'Unblock' }}">
                                            <i class="fa-solid {{ $user->status === 'active' ? 'fa-ban' : 'fa-circle-check' }} text-xs"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100" title="Edit">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?');">
                                        @csrf @method('DELETE')
                                        <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100" title="Delete">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-users text-3xl mb-3 block"></i>
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800">
                {{ $users->links() }}
            </div>
        @endif
    </div>

@endsection

@extends('admin.layouts.app')

@section('title', 'Add User')

@section('content')

    @php
        $label = 'block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5';
        $input = 'w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500';
    @endphp

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.users.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-lg bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-slate-500 hover:text-slate-800">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-semibold text-slate-800 dark:text-white">Add User</h1>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="{{ $label }}">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="{{ $input }}" required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" class="{{ $input }}" required>
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" class="{{ $input }}" required>
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="{{ $label }}">Role</label>
                    <select name="role" class="{{ $input }}">
                        @foreach (['student','instructor','organization','admin'] as $r)
                            <option value="{{ $r }}" {{ old('role','student') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="{{ $label }}">Status</label>
                    <select name="status" class="{{ $input }}">
                        <option value="active" {{ old('status','active') === 'blocked' ? '' : 'selected' }}>Active</option>
                        <option value="blocked" {{ old('status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">Save User</button>
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition text-sm">Cancel</a>
            </div>
        </form>
    </div>

@endsection

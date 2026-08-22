@extends('admin.layouts.app')

@section('title', 'Certificate Settings')

@section('content')

    @php
        $label = 'block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5';
        $input = 'w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500';
    @endphp

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.certificates.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-lg bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-slate-500 hover:text-slate-800">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-semibold text-slate-800 dark:text-white">Certificate Settings</h1>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-6 max-w-2xl">
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">
            These appear on every auto-generated online certificate. Change them anytime — no code needed.
        </p>

        <form method="POST" action="{{ route('admin.certificate-settings.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="{{ $label }}">Academy name <span class="text-red-500">*</span></label>
                <input type="text" name="academy_name" value="{{ old('academy_name', $settings->academy_name) }}" class="{{ $input }}" required>
                @error('academy_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="{{ $label }}">Signatory name</label>
                    <input type="text" name="signatory_name" value="{{ old('signatory_name', $settings->signatory_name) }}" class="{{ $input }}" placeholder="e.g. Md. Rahman">
                </div>
                <div>
                    <label class="{{ $label }}">Signatory title</label>
                    <input type="text" name="signatory_title" value="{{ old('signatory_title', $settings->signatory_title) }}" class="{{ $input }}" placeholder="e.g. Director, Kolom Academy">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="{{ $label }}">Logo</label>
                    @if ($settings->logo)
                        <img src="{{ asset('storage/' . $settings->logo) }}" class="h-12 mb-2 object-contain">
                    @endif
                    <input type="file" name="logo" accept="image/*" class="{{ $input }}">
                    <p class="text-xs text-slate-400 mt-1">PNG with transparent background works best.</p>
                </div>
                <div>
                    <label class="{{ $label }}">Signature image</label>
                    @if ($settings->signature)
                        <img src="{{ asset('storage/' . $settings->signature) }}" class="h-12 mb-2 object-contain">
                    @endif
                    <input type="file" name="signature" accept="image/*" class="{{ $input }}">
                    <p class="text-xs text-slate-400 mt-1">A scanned signature (PNG) looks best.</p>
                </div>
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                Save Settings
            </button>
        </form>
    </div>

@endsection

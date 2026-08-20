@extends('admin.layouts.app')

@section('title', 'Edit Course')

@section('content')

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.courses.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-lg bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-slate-500 hover:text-slate-800">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-semibold text-slate-800 dark:text-white">Edit Course</h1>
    </div>

    @if ($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            Please fix the highlighted fields below.
        </div>
    @endif

    <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        @php
            $label = 'block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5';
            $input = 'w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500';
        @endphp

        {{-- Main details --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-6">
            <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4">Course details</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="{{ $label }}">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $course->title) }}" class="{{ $input }}" required>
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $label }}">School (Category)</label>
                    <select name="category_id" class="{{ $input }}">
                        <option value="">— Select —</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $course->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="{{ $label }}">Type <span class="text-red-500">*</span></label>
                    <select name="type" class="{{ $input }}">
                        <option value="offline" {{ old('type', $course->type) === 'offline' ? 'selected' : '' }}>Offline (physical)</option>
                        <option value="online" {{ old('type', $course->type) === 'online' ? 'selected' : '' }}>Online</option>
                    </select>
                </div>

                <div>
                    <label class="{{ $label }}">Level</label>
                    <select name="level" class="{{ $input }}">
                        <option value="">— Select —</option>
                        <option value="beginner" {{ old('level', $course->level) === 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('level', $course->level) === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ old('level', $course->level) === 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                </div>

                <div>
                    <label class="{{ $label }}">Language</label>
                    <input type="text" name="language" value="{{ old('language', $course->language) }}" class="{{ $input }}">
                </div>

                <div>
                    <label class="{{ $label }}">Duration</label>
                    <input type="text" name="duration" value="{{ old('duration', $course->duration) }}" class="{{ $input }}">
                </div>

                <div>
                    <label class="{{ $label }}">Instructor</label>
                    <select name="instructor_id" class="{{ $input }}">
                        <option value="">— None —</option>
                        @foreach ($instructors as $ins)
                            <option value="{{ $ins->id }}" {{ old('instructor_id', $course->instructor_id) == $ins->id ? 'selected' : '' }}>{{ $ins->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="{{ $label }}">Thumbnail image</label>
                    @if ($course->thumbnail)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="" class="w-32 h-20 object-cover rounded-md border border-gray-200 dark:border-slate-700">
                        </div>
                    @endif
                    <input type="file" name="thumbnail" accept="image/*" class="{{ $input }}">
                    <p class="text-xs text-slate-400 mt-1">Upload a new image to replace the current one. Max 2MB.</p>
                    @error('thumbnail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Descriptions --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-6">
            <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4">Descriptions</h2>
            <div class="space-y-5">
                <div>
                    <label class="{{ $label }}">Short description</label>
                    <textarea name="short_description" rows="2" class="{{ $input }}">{{ old('short_description', $course->short_description) }}</textarea>
                </div>
                <div>
                    <label class="{{ $label }}">Full description</label>
                    <textarea name="description" rows="4" class="{{ $input }}">{{ old('description', $course->description) }}</textarea>
                </div>
                <div>
                    <label class="{{ $label }}">Outcome / career paths</label>
                    <textarea name="outcome" rows="2" class="{{ $input }}">{{ old('outcome', $course->outcome) }}</textarea>
                </div>
                <div>
                    <label class="{{ $label }}">Final / capstone project</label>
                    <textarea name="final_project" rows="2" class="{{ $input }}">{{ old('final_project', $course->final_project) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Pricing & settings --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-6">
            <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4">Pricing &amp; settings</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="{{ $label }}">Price</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $course->price) }}" class="{{ $input }}">
                </div>
                <div>
                    <label class="{{ $label }}">Sale price</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $course->sale_price) }}" class="{{ $input }}">
                </div>
                <div>
                    <label class="{{ $label }}">Status</label>
                    <select name="status" class="{{ $input }}">
                        <option value="draft" {{ old('status', $course->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $course->status) === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div class="flex items-center gap-2 mt-7">
                    <input type="checkbox" name="certificate" id="certificate" value="1" {{ old('certificate', $course->certificate) ? 'checked' : '' }} class="w-4 h-4">
                    <label for="certificate" class="text-sm text-slate-700 dark:text-slate-300">Offer certificate on completion</label>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">Update Course</button>
            <a href="{{ route('admin.courses.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition text-sm">Cancel</a>
        </div>
    </form>

@endsection

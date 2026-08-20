@extends('admin.layouts.app')

@section('title', 'Categories')

@section('content')

    {{-- Header row --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-white">Categories</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage your course categories</p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2.5 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
            <i class="fa-solid fa-plus"></i> Add Category
        </a>
    </div>

    {{-- Table card --}}
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-left">
                    <tr>
                        <th class="px-6 py-3 font-medium">#</th>
                        <th class="px-6 py-3 font-medium">Name</th>
                        <th class="px-6 py-3 font-medium">Slug</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50">
                            <td class="px-6 py-4 text-slate-400">{{ $category->id }}</td>
                            <td class="px-6 py-4 font-medium text-slate-800 dark:text-white">{{ $category->name }}</td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400">{{ $category->slug }}</td>
                            <td class="px-6 py-4">
                                @if ($category->status === 'active')
                                    <span class="inline-block px-2.5 py-1 text-xs rounded-full bg-green-100 text-green-700">Active</span>
                                @else
                                    <span class="inline-block px-2.5 py-1 text-xs rounded-full bg-gray-200 text-gray-600">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition"
                                       title="Edit">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                          onsubmit="return confirm('Delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition"
                                                title="Delete">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-layer-group text-3xl mb-3 block"></i>
                                No categories yet. Click "Add Category" to create your first one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

@endsection

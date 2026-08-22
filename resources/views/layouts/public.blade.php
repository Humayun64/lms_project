<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Courses') - Kolom Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-800">

    {{-- Header --}}
    <header class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('courses.index') }}" class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <span class="text-lg font-semibold">Kolom Academy</span>
            </a>
            <nav class="flex items-center gap-5 text-sm">
                <a href="{{ route('courses.index') }}" class="text-slate-600 hover:text-indigo-600">Courses</a>
                @auth
                    @php
                        $dash = match(auth()->user()->role) {
                            'admin'        => route('admin.dashboard'),
                            'instructor'   => route('instructor.dashboard'),
                            'organization' => route('organization.dashboard'),
                            default        => route('student.dashboard'),
                        };
                    @endphp
                    <a href="{{ $dash }}" class="text-slate-600 hover:text-indigo-600">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Login</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="border-t border-gray-200 mt-16">
        <div class="max-w-6xl mx-auto px-4 py-8 text-center text-sm text-slate-400">
            &copy; {{ date('Y') }} Kolom Academy. All rights reserved.
        </div>
    </footer>

</body>
</html>

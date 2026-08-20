<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Learning') - Kolom Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-50 text-slate-800">

    {{-- Top nav --}}
    <header class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <span class="text-lg font-semibold text-slate-800">Kolom Academy</span>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('student.dashboard') }}" class="text-sm text-slate-600 hover:text-indigo-600">My Courses</a>
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-sm font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-red-500 hover:text-red-600"><i class="fa-solid fa-right-from-bracket"></i></button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8">
        @if (session('success'))
            <div class="mb-5 flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-5 flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>

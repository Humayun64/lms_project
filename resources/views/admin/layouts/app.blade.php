<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - LMS Admin</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
        if (localStorage.getItem('admin-theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        ::-webkit-scrollbar{ width:8px; height:8px; }
        ::-webkit-scrollbar-thumb{ background:#cbd5e1; border-radius:4px; }
        .dark ::-webkit-scrollbar-thumb{ background:#334155; }
    </style>
</head>
<body class="bg-gray-100 dark:bg-slate-950 text-slate-800 dark:text-slate-200">

<div class="flex min-h-screen">

    <aside id="sidebar"
           class="fixed lg:static inset-y-0 left-0 z-40 w-64 bg-slate-900 text-slate-300
                  transform -translate-x-full lg:translate-x-0 transition-transform duration-200
                  flex flex-col">

        <div class="flex items-center gap-3 px-5 h-16 border-b border-slate-800">
            <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <span class="text-white text-lg font-semibold">LMS Admin</span>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 text-sm">
            @php
                $active   = 'flex items-center gap-3 px-3 py-2.5 rounded-lg bg-indigo-600 text-white';
                $inactive = 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition';
            @endphp

            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? $active : $inactive }}">
                <i class="fa-solid fa-gauge-high w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('admin.courses.index') }}" class="{{ request()->routeIs('admin.courses.*') || request()->routeIs('admin.curriculum.*') || request()->routeIs('admin.quiz.*') ? $active : $inactive }}">
                <i class="fa-solid fa-book w-5 text-center"></i> Courses
            </a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? $active : $inactive }}">
                <i class="fa-solid fa-layer-group w-5 text-center"></i> Categories
            </a>
            <a href="{{ route('admin.certificates.index') }}" class="{{ request()->routeIs('admin.certificates.*') || request()->routeIs('admin.certificate-settings.*') ? $active : $inactive }}">
                <i class="fa-solid fa-certificate w-5 text-center"></i> Certificates
            </a>

            {{-- Wired up as we build each module --}}
            <a href="#" class="{{ $inactive }}"><i class="fa-solid fa-users w-5 text-center"></i> Students</a>
            <a href="#" class="{{ $inactive }}"><i class="fa-solid fa-chalkboard-user w-5 text-center"></i> Instructors</a>
            <a href="#" class="{{ $inactive }}"><i class="fa-solid fa-building w-5 text-center"></i> Organizations</a>
            <a href="#" class="{{ $inactive }}"><i class="fa-solid fa-cart-shopping w-5 text-center"></i> Orders</a>
            <a href="#" class="{{ $inactive }}"><i class="fa-solid fa-ticket w-5 text-center"></i> Coupons</a>
            <a href="#" class="{{ $inactive }}"><i class="fa-solid fa-chart-line w-5 text-center"></i> Reports</a>
            <a href="#" class="{{ $inactive }}"><i class="fa-solid fa-gear w-5 text-center"></i> Settings</a>
        </nav>

        <div class="p-3 border-t border-slate-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-400 hover:bg-slate-800 transition text-sm">
                    <i class="fa-solid fa-right-from-bracket w-5 text-center"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <div id="overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

    <div class="flex-1 flex flex-col min-w-0">

        <header class="h-16 bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800 flex items-center gap-4 px-4 lg:px-6 sticky top-0 z-20">
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-500 dark:text-slate-400">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>

            <div class="hidden sm:flex items-center gap-2 bg-gray-100 dark:bg-slate-800 rounded-lg px-3 py-2 w-64">
                <i class="fa-solid fa-magnifying-glass text-slate-400 text-sm"></i>
                <input type="text" placeholder="Search..." class="bg-transparent outline-none text-sm w-full text-slate-700 dark:text-slate-200 placeholder-slate-400">
            </div>

            <div class="ml-auto flex items-center gap-4 text-slate-500 dark:text-slate-400">
                <span class="hidden sm:flex items-center gap-1 text-sm cursor-pointer hover:text-slate-700 dark:hover:text-slate-200">
                    <i class="fa-solid fa-globe"></i> EN
                </span>
                <button onclick="toggleTheme()" class="hover:text-slate-700 dark:hover:text-slate-200" title="Toggle theme">
                    <i id="themeIcon" class="fa-solid fa-moon text-lg"></i>
                </button>
                <button class="relative hover:text-slate-700 dark:hover:text-slate-200">
                    <i class="fa-solid fa-bell text-lg"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">5</span>
                </button>
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-sm font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <span class="hidden sm:block text-sm text-slate-700 dark:text-slate-200">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 lg:p-6">
            @if (session('success'))
                <div class="mb-5 flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
        document.getElementById('overlay').classList.toggle('hidden');
    }
    function toggleTheme() {
        const html = document.documentElement;
        html.classList.toggle('dark');
        const isDark = html.classList.contains('dark');
        localStorage.setItem('admin-theme', isDark ? 'dark' : 'light');
        document.getElementById('themeIcon').className = isDark ? 'fa-solid fa-sun text-lg' : 'fa-solid fa-moon text-lg';
    }
    if (document.documentElement.classList.contains('dark')) {
        document.getElementById('themeIcon').className = 'fa-solid fa-sun text-lg';
    }
</script>
</body>
</html>

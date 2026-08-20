<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="max-w-4xl mx-auto py-16 px-4">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">

            {{-- Header bar --}}
            <div class="bg-slate-900 text-white px-8 py-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Instructor Portal</h1>
                    <p class="text-slate-300 text-sm mt-1">Your workspace</p>
                </div>
                <span class="bg-emerald-500 text-white text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wide">
                    {{ Auth::user()->role }}
                </span>
            </div>

            {{-- Body --}}
            <div class="px-8 py-8">
                <h2 class="text-xl font-semibold text-gray-800">
                    Welcome back, {{ Auth::user()->name }} 👋
                </h2>
                <p class="text-gray-600 mt-2">
                    You are logged in as <strong>{{ Auth::user()->email }}</strong>.
                </p>

                <div class="mt-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded p-4 text-sm">
                    ✅ Authentication is working. This is a placeholder instructor dashboard —
                    we will build out the real features here later.
                </div>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="mt-8">
                    @csrf
                    <button type="submit"
                            class="bg-red-600 text-white px-5 py-2 rounded hover:bg-red-700 transition">
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </div>

</body>
</html>

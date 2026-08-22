<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Certificate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-md w-full max-w-md p-8 text-center">
        @if ($certificate)
            <div class="w-16 h-16 rounded-full bg-green-100 text-green-600 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-circle-check text-3xl"></i>
            </div>
            <h1 class="text-xl font-semibold text-slate-800 mb-1">Valid Certificate</h1>
            <p class="text-sm text-slate-500 mb-6">This certificate is genuine and verified.</p>

            <div class="text-left bg-gray-50 rounded-lg p-4 text-sm space-y-2">
                <div class="flex justify-between"><span class="text-slate-500">Name</span><span class="font-medium text-slate-800">{{ $certificate->user->name }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Course</span><span class="font-medium text-slate-800">{{ $certificate->course->title }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Issued</span><span class="font-medium text-slate-800">{{ $certificate->issued_at?->format('d M Y') }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">ID</span><span class="font-mono text-xs text-slate-800">{{ $certificate->certificate_number }}</span></div>
            </div>
        @else
            <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-circle-xmark text-3xl"></i>
            </div>
            <h1 class="text-xl font-semibold text-slate-800 mb-1">Not Found</h1>
            <p class="text-sm text-slate-500">No certificate matches the ID <span class="font-mono">{{ $number }}</span>.</p>
        @endif
    </div>

</body>
</html>

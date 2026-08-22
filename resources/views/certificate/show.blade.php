<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - {{ $certificate->course->title ?? '' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        .font-serif-display { font-family: 'Playfair Display', serif; }
        .font-script { font-family: 'Great Vibes', cursive; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            @page { size: landscape; margin: 0; }
        }
    </style>
</head>
<body class="bg-slate-100 py-8">

    <div class="no-print max-w-4xl mx-auto mb-6 flex items-center justify-between px-4">
        <a href="javascript:history.back()" class="text-sm text-slate-600 hover:text-indigo-600">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back
        </a>
        <button onclick="window.print()" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 text-sm font-medium">
            <i class="fa-solid fa-download mr-1"></i> Print / Save as PDF
        </button>
    </div>

    <div class="max-w-4xl mx-auto bg-white shadow-xl">
        <div class="m-4 border-4 border-indigo-900 p-10 text-center relative">
            <div class="absolute inset-2 border border-indigo-200 pointer-events-none"></div>

            <div class="mb-6">
                @if ($settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo" class="h-16 mx-auto mb-2 object-contain">
                @endif
                <div class="text-xl font-serif-display text-indigo-900">{{ $settings->academy_name }}</div>
            </div>

            <div class="uppercase tracking-[0.3em] text-xs text-slate-500 mb-2">Certificate of Completion</div>
            <div class="w-24 h-1 bg-indigo-900 mx-auto mb-8"></div>

            <p class="text-sm text-slate-500 mb-2">This is proudly presented to</p>
            <div class="font-script text-6xl text-indigo-900 mb-4">{{ $certificate->display_name }}</div>

            <p class="text-sm text-slate-600 max-w-xl mx-auto mb-8">
                for successfully completing the course
                <span class="font-semibold text-slate-800">"{{ $certificate->course->title ?? '' }}"</span>
                @if ($certificate->course && $certificate->course->duration) <br>Duration: {{ $certificate->course->duration }} @endif
            </p>

            <div class="flex items-end justify-between mt-12 px-6">
                <div class="text-center">
                    <div class="text-sm font-medium text-slate-700">{{ $certificate->issued_at?->format('d M Y') }}</div>
                    <div class="border-t border-slate-400 mt-1 pt-1 text-xs text-slate-500">Date</div>
                </div>
                <div class="text-center">
                    <i class="fa-solid fa-award text-4xl text-indigo-900"></i>
                </div>
                <div class="text-center">
                    @if ($settings->signature)
                        <img src="{{ asset('storage/' . $settings->signature) }}" alt="Signature" class="h-10 mx-auto object-contain">
                    @else
                        <div class="h-10"></div>
                    @endif
                    <div class="border-t border-slate-400 mt-1 pt-1 text-xs text-slate-500">
                        {{ $settings->signatory_name ?: 'Authorized Signature' }}<br>
                        <span class="text-[10px]">{{ $settings->signatory_title }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-[11px] text-slate-400">
                Certificate ID: <span class="font-mono">{{ $certificate->certificate_number }}</span>
                &nbsp;·&nbsp; Verify at {{ url('/verify/' . $certificate->certificate_number) }}
            </div>
        </div>
    </div>

</body>
</html>

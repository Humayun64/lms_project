@extends('layouts.public')

@section('title', $course->title)

@section('content')

    {{-- Course header --}}
    <div class="bg-indigo-900 text-white">
        <div class="max-w-6xl mx-auto px-4 py-10 grid md:grid-cols-3 gap-8 items-center">
            <div class="md:col-span-2">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-xs bg-white/10 px-2 py-1 rounded-full">{{ $course->category->name ?? '' }}</span>
                    @if ($course->type === 'online')
                        <span class="text-xs bg-emerald-500/20 text-emerald-200 px-2 py-1 rounded-full"><i class="fa-solid fa-wifi mr-1"></i> Online</span>
                    @else
                        <span class="text-xs bg-orange-500/20 text-orange-200 px-2 py-1 rounded-full"><i class="fa-solid fa-location-dot mr-1"></i> Offline (in person)</span>
                    @endif
                </div>
                <h1 class="text-3xl font-semibold mb-3">{{ $course->title }}</h1>
                <p class="text-indigo-200 mb-4">{{ $course->short_description }}</p>
                <div class="flex flex-wrap gap-4 text-sm text-indigo-200">
                    @if ($course->duration) <span><i class="fa-solid fa-clock mr-1"></i> {{ $course->duration }}</span> @endif
                    @if ($course->level) <span><i class="fa-solid fa-signal mr-1"></i> {{ ucfirst($course->level) }}</span> @endif
                    @if ($course->language) <span><i class="fa-solid fa-language mr-1"></i> {{ $course->language }}</span> @endif
                </div>
            </div>
            <div>
                <div class="rounded-xl overflow-hidden bg-white/10 h-44">
                    @if ($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" class="w-full h-full object-cover" alt="">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-white/40"><i class="fa-solid fa-image text-4xl"></i></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-10 grid md:grid-cols-3 gap-8">

        {{-- Left: details + module list --}}
        <div class="md:col-span-2 space-y-8">

            @if ($course->description)
                <section>
                    <h2 class="text-xl font-semibold text-slate-800 mb-3">About this course</h2>
                    <p class="text-slate-600 leading-relaxed whitespace-pre-line">{{ $course->description }}</p>
                </section>
            @endif

            @if ($course->outcome)
                <section>
                    <h2 class="text-xl font-semibold text-slate-800 mb-3">What you'll become</h2>
                    <p class="text-slate-600 whitespace-pre-line">{{ $course->outcome }}</p>
                </section>
            @endif

            {{-- Module list (curriculum) — visible to everyone --}}
            <section>
                <h2 class="text-xl font-semibold text-slate-800 mb-3">Course Modules</h2>
                @if ($course->sections->isEmpty())
                    <p class="text-slate-400 text-sm">Module details coming soon.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($course->sections as $section)
                            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                                <div class="px-5 py-3 bg-gray-50 font-medium text-slate-800 flex items-center gap-2">
                                    <i class="fa-solid fa-folder text-indigo-500"></i> {{ $section->title }}
                                    <span class="text-xs text-slate-400 font-normal">({{ $section->lessons->count() }} topics)</span>
                                </div>
                                <div class="divide-y divide-gray-100">
                                    @foreach ($section->lessons as $lesson)
                                        <div class="px-5 py-3 flex items-center gap-3 text-sm text-slate-600">
                                            @php
                                                $ic = match($lesson->type) {
                                                    'video' => 'fa-play', 'text' => 'fa-align-left',
                                                    'pdf' => 'fa-file-pdf', 'quiz' => 'fa-circle-question', default => 'fa-file',
                                                };
                                            @endphp
                                            <i class="fa-solid {{ $ic }} text-slate-400"></i>
                                            <span class="flex-1">{{ $lesson->title }}</span>
                                            @if ($course->type === 'online' && $lesson->is_preview)
                                                <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Free preview</span>
                                            @endif
                                            @if ($lesson->duration) <span class="text-xs text-slate-400">{{ $lesson->duration }}</span> @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>

        {{-- Right: action box --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6 sticky top-20">

                {{-- ===== ONLINE course: enroll ===== --}}
                @if ($course->type === 'online')
                    <div class="text-2xl font-semibold text-slate-800 mb-4">
                        {{ $course->price ? '$' . number_format($course->price, 2) : 'Free' }}
                    </div>
                    @auth
                        @if (auth()->user()->role === 'student')
                            @if (auth()->user()->isEnrolledIn($course->id))
                                <a href="{{ route('student.learn', $course) }}" class="block text-center bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 font-medium">Go to Course</a>
                            @else
                                <form method="POST" action="{{ route('student.enroll', $course) }}">
                                    @csrf
                                    <button class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 font-medium">Enroll Now</button>
                                </form>
                            @endif
                        @else
                            <p class="text-sm text-slate-500">Log in as a student to enroll.</p>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block text-center bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 font-medium">Login to Enroll</a>
                    @endauth
                    <ul class="mt-5 space-y-2 text-sm text-slate-500">
                        <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Learn at your own pace</li>
                        <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Quizzes & progress tracking</li>
                        @if ($course->certificate)<li><i class="fa-solid fa-check text-green-500 mr-2"></i> Certificate on completion</li>@endif
                    </ul>

                {{-- ===== OFFLINE course: register ===== --}}
                @else
                    <h3 class="text-lg font-semibold text-slate-800 mb-1">Register for this course</h3>
                    <p class="text-sm text-slate-500 mb-4">This is an in-person course held at our academy.</p>

                    {{-- Batches --}}
                    @if ($batches->isNotEmpty())
                        <div class="mb-4 space-y-2">
                            <div class="text-xs font-semibold text-slate-500 uppercase">Upcoming batches</div>
                            @foreach ($batches as $b)
                                <div class="text-sm bg-gray-50 rounded-lg p-3">
                                    <div class="font-medium text-slate-700">{{ $b->name }}</div>
                                    <div class="text-xs text-slate-500 mt-1 space-y-0.5">
                                        @if ($b->start_date) <div><i class="fa-solid fa-calendar mr-1"></i> Starts {{ $b->start_date->format('d M Y') }}</div> @endif
                                        @if ($b->schedule) <div><i class="fa-solid fa-clock mr-1"></i> {{ $b->schedule }}</div> @endif
                                        @if ($b->venue) <div><i class="fa-solid fa-location-dot mr-1"></i> {{ $b->venue }}</div> @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if (session('registered'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm mb-4">
                            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('registered') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-4">{{ $errors->first() }}</div>
                    @endif

                    @php
                        $fld = 'w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 mb-3';
                    @endphp
                    <form method="POST" action="{{ route('courses.register', $course) }}">
                        @csrf
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="Your name" class="{{ $fld }}" required>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="Email" class="{{ $fld }}" required>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone number" class="{{ $fld }}" required>

                        @if ($batches->isNotEmpty())
                            <select name="batch_id" class="{{ $fld }}">
                                <option value="">Preferred batch (optional)</option>
                                @foreach ($batches as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        @endif

                        {{-- Payment method based on course setting --}}
                        @if ($course->offline_payment === 'both')
                            <div class="mb-3 text-sm">
                                <div class="text-xs font-semibold text-slate-500 uppercase mb-1">Payment</div>
                                <label class="flex items-center gap-2 mb-1"><input type="radio" name="payment_method" value="in_person" checked> Pay in person at office</label>
                                <label class="flex items-center gap-2"><input type="radio" name="payment_method" value="online"> Pay online (we'll send details)</label>
                            </div>
                        @elseif ($course->offline_payment === 'online')
                            <input type="hidden" name="payment_method" value="online">
                            <p class="text-xs text-slate-500 mb-3">Payment: online — our team will share payment details after you register.</p>
                        @else
                            <input type="hidden" name="payment_method" value="in_person">
                            <p class="text-xs text-slate-500 mb-3">Payment: in person at our office.</p>
                        @endif

                        <button class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 font-medium">Register</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

@endsection

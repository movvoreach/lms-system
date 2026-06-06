@extends('website.layout')

@section('title', $course->title . ' | SPI Learning')

@section('nav-search')
    <div class="chip-row">
        <a class="chip" href="{{ route('website.home') }}">
            <i class="fas fa-arrow-left" style="margin-right:8px"></i>
            វគ្គសិក្សាទាំងអស់
        </a>
        @foreach($relatedCourses as $relatedCourse)
            <a class="chip" href="{{ route('website.courses.show', $relatedCourse) }}">{{ $relatedCourse->title }}</a>
        @endforeach
    </div>
@endsection

@section('content')
    @php
        $title = strtolower($course->title);
        $style = str_contains($title, 'python') ? 'python'
            : (str_contains($title, 'git') ? 'git'
            : (str_contains($title, 'php') || str_contains($title, 'laravel') ? 'php'
            : (str_contains($title, 'html') || str_contains($title, 'css') || str_contains($title, 'javascript') || str_contains($title, 'web') ? 'web'
            : (str_contains($title, 'java') ? 'java' : 'default'))));
        $icon = match ($style) {
            'python' => 'fab fa-python',
            'git' => 'fab fa-git-alt',
            'php' => str_contains($title, 'laravel') ? 'fab fa-laravel' : 'fab fa-php',
            'web' => 'fab fa-html5',
            'java' => 'fab fa-java',
            default => 'fas fa-code',
        };
    @endphp

    <main class="page">
        <section class="course-shell">
            <aside class="module-sidebar">
                @forelse($modules as $moduleNumber => $moduleLessons)
                    <div class="module-title">
                        <span class="module-number">{{ $moduleNumber }}</span>
                        <span>{{ $moduleLessons->first()->module_title ?: 'ម៉ូឌុលទី ' . $moduleNumber }}</span>
                        <i class="fas fa-chevron-right" style="margin-left:auto"></i>
                    </div>

                    <ul class="lesson-list">
                        @foreach($moduleLessons as $lesson)
                            <li>
                                <a class="{{ optional($selectedLesson)->id === $lesson->id ? 'active' : '' }}"
                                   href="{{ route('website.courses.show', ['course' => $course, 'lesson' => $lesson->slug]) }}">
                                    <span class="lesson-dot"></span>
                                    <span>{{ $moduleNumber }}.{{ $lesson->position }} {{ $lesson->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @empty
                    <div class="empty-state">មិនទាន់មានមេរៀនសម្រាប់វគ្គនេះ</div>
                @endforelse
            </aside>

            <article class="lesson-content">
                @if($selectedLesson)
                    <h1>{{ $selectedLesson->module_number }}. {{ $selectedLesson->title }}</h1>

                    <div class="lesson-hero">
                        @if($selectedLesson->file_path && in_array(strtolower(pathinfo($selectedLesson->file_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif']))
                            <img src="{{ asset('storage/' . $selectedLesson->file_path) }}" alt="{{ $selectedLesson->title }}">
                        @else
                            <div class="course-art {{ $style }}">
                                <div class="logo-mark">
                                    <i class="{{ $icon }}"></i>
                                    <b>{{ $course->code ?: \Illuminate\Support\Str::limit($course->title, 12, '') }}</b>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="course-meta" style="margin-bottom:24px">
                        <span><i class="fas fa-layer-group"></i> {{ $selectedLesson->module_title ?: 'ម៉ូឌុលទី ' . $selectedLesson->module_number }}</span>
                        <span><i class="fas fa-shapes"></i> {{ ucfirst($selectedLesson->content_type) }}</span>
                        @if($selectedLesson->duration_minutes)
                            <span><i class="far fa-clock"></i> {{ $selectedLesson->duration_minutes }} នាទី</span>
                        @endif
                    </div>

                    <div class="lesson-text">
                        @if($selectedLesson->summary)
                            <p>{{ $selectedLesson->summary }}</p>
                        @endif

                        @if($selectedLesson->video_url)
                            <p>
                                <a class="course-button" href="{{ $selectedLesson->video_url }}" target="_blank" rel="noopener">
                                    <i class="fas fa-play-circle" style="margin-right:10px"></i>
                                    មើលវីដេអូមេរៀន
                                </a>
                            </p>
                        @endif

                        @if($selectedLesson->external_url)
                            <p>
                                <a class="course-button" href="{{ $selectedLesson->external_url }}" target="_blank" rel="noopener">
                                    <i class="fas fa-external-link-alt" style="margin-right:10px"></i>
                                    បើកធនធានខាងក្រៅ
                                </a>
                            </p>
                        @endif

                        @if($selectedLesson->body)
                            {!! $selectedLesson->body !!}
                        @else
                            <h2>សេចក្តីណែនាំ</h2>
                            <p>{{ $course->description ?: 'មេរៀននេះកំពុងរៀបចំខ្លឹមសារលម្អិត។ សូមត្រឡប់មកមើលម្តងទៀតនៅពេលក្រោយ។' }}</p>
                            <h2>អ្វីដែលអ្នកនឹងរៀន</h2>
                            <p>អ្នកនឹងរៀនគោលគំនិតសំខាន់ៗ តាមលំដាប់ម៉ូឌុល និងអនុវត្តជាមួយឧទាហរណ៍ជាក់ស្តែង។</p>
                        @endif

                        @if($selectedLesson->file_path && ! in_array(strtolower(pathinfo($selectedLesson->file_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif']))
                            <p>
                                <a class="course-button" href="{{ asset('storage/' . $selectedLesson->file_path) }}" target="_blank" rel="noopener">
                                    <i class="fas fa-download" style="margin-right:10px"></i>
                                    ទាញយកឯកសារមេរៀន
                                </a>
                            </p>
                        @endif
                    </div>

                    <section class="comments">
                        <h2>សំណួរ និងមតិយោបល់</h2>
                        <div class="comment">
                            <span class="avatar-dot">គ</span>
                            <div>
                                <strong>គ្រូបង្រៀន</strong>
                                <p>បើមានចម្ងល់អំពីមេរៀននេះ សូមសួរនៅទីនេះ។ គ្រូនឹងជួយពន្យល់បន្ថែម។</p>
                            </div>
                        </div>
                        <div class="comment">
                            <span class="avatar-dot">ស</span>
                            <div>
                                <strong>សិស្ស</strong>
                                <p>ខ្ញុំចង់បានឧទាហរណ៍បន្ថែមសម្រាប់អនុវត្ត។</p>
                            </div>
                        </div>
                        <form class="comment-form">
                            <textarea placeholder="សរសេរសំណួរ ឬមតិយោបល់របស់អ្នក..."></textarea>
                            <button class="primary-action" type="button">បញ្ចូលមតិយោបល់</button>
                        </form>
                    </section>
                @else
                    <div class="empty-state">
                        <h2>{{ $course->title }}</h2>
                        <p>វគ្គនេះមិនទាន់មានមេរៀនដែលអាចបង្ហាញបានទេ។</p>
                    </div>
                @endif
            </article>
        </section>
    </main>
@endsection

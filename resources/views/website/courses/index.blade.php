@extends('website.layout')

@section('title', 'វគ្គសិក្សា | SPI Learning')

@section('nav-search')
    <label class="search-box" for="courseSearch">
        <i class="fas fa-search text-muted"></i>
        <input id="courseSearch" type="search" placeholder="ស្វែងរកវគ្គសិក្សា...">
    </label>

    <div class="chip-row" id="categoryFilters">
        <button class="chip active" type="button" data-category="all">ទាំងអស់</button>
        @foreach($categories as $category)
            <button class="chip" type="button" data-category="{{ $category->id }}">{{ $category->name }}</button>
        @endforeach
    </div>
@endsection

@section('content')
    <main class="page">
        <section class="page-title">
            <h1>មេរៀនដែលមាន</h1>
            <p>ជ្រើសរើសវគ្គសិក្សា ដើម្បីបើកមើលមេរៀនតាមម៉ូឌុល សកម្មភាព និងឯកសារសិក្សាដូច Moodle។</p>
        </section>

        @if($courses->isNotEmpty() || $staticCourses->isNotEmpty())
            <section class="course-grid" id="courseGrid">
                @foreach($courses as $course)
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

                    <article class="course-card" data-title="{{ strtolower($course->title . ' ' . $course->description) }}" data-category="{{ $course->category_id }}">
                        <div class="course-art {{ $style }}">
                            <div class="logo-mark">
                                <i class="{{ $icon }}"></i>
                                <b>{{ $course->code ?: \Illuminate\Support\Str::limit($course->title, 12, '') }}</b>
                            </div>
                        </div>

                        <div class="course-body">
                            <span class="badge-soft">{{ $course->category->name ?? 'វគ្គសិក្សា' }}</span>
                            <h2>{{ $course->title }}</h2>
                            <p>{{ \Illuminate\Support\Str::limit($course->description ?: 'វគ្គសិក្សានេះមានមេរៀនជាច្រើន សម្រាប់ជួយអ្នករៀនតាមលំដាប់ និងអនុវត្តជាក់ស្តែង។', 180) }}</p>

                            <div class="course-meta">
                                <span><i class="fas fa-book-open"></i> {{ $course->lesson_contents_count ?? $course->lessonContents->count() }} មេរៀន</span>
                                @if($course->duration_hours)
                                    <span><i class="far fa-clock"></i> {{ $course->duration_hours }} ម៉ោង</span>
                                @endif
                            </div>

                            <a class="course-button" href="{{ route('website.courses.show', $course) }}">
                                👉 ចូលរៀនឥឡូវនេះ
                            </a>
                        </div>
                    </article>
                @endforeach

                @foreach($staticCourses as $course)
                    <article class="course-card" data-title="{{ strtolower($course['title'] . ' ' . $course['description']) }}" data-category="static">
                        <div class="course-art {{ $course['style'] }}">
                            <div class="logo-mark">
                                <i class="{{ $course['icon'] }}"></i>
                                <b>{{ $course['code'] }}</b>
                            </div>
                        </div>

                        <div class="course-body">
                            <span class="badge-soft">{{ $course['category'] }}</span>
                            <h2>{{ $course['title'] }}</h2>
                            <p>{{ $course['description'] }}</p>

                            <div class="course-meta">
                                <span><i class="fas fa-book-open"></i> {{ collect($course['modules'])->sum(fn ($module) => count($module['lessons'])) }} មេរៀន</span>
                                <span><i class="far fa-clock"></i> {{ $course['duration'] }} ម៉ោង</span>
                            </div>

                            <a class="course-button" href="{{ route('website.static-course.show', $course['slug']) }}">
                                👉 ចូលរៀនឥឡូវនេះ
                            </a>
                        </div>
                    </article>
                @endforeach
            </section>
        @else
            <div class="empty-state">
                <h2>មិនទាន់មានវគ្គសិក្សាសម្រាប់បង្ហាញ</h2>
                <p>សូមបង្កើតវគ្គសិក្សា និងមេរៀនដែលបាន publish នៅផ្នែកគ្រប់គ្រង។</p>
            </div>
        @endif
    </main>
@endsection

@push('scripts')
<script>
    const searchInput = document.getElementById('courseSearch');
    const filterButtons = document.querySelectorAll('#categoryFilters .chip');
    const cards = document.querySelectorAll('.course-card');
    let activeCategory = 'all';

    function filterCourses() {
        const query = (searchInput?.value || '').trim().toLowerCase();

        cards.forEach((card) => {
            const matchesSearch = !query || card.dataset.title.includes(query);
            const matchesCategory = activeCategory === 'all' || card.dataset.category === activeCategory;
            card.style.display = matchesSearch && matchesCategory ? '' : 'none';
        });
    }

    searchInput?.addEventListener('input', filterCourses);

    filterButtons.forEach((button) => {
        button.addEventListener('click', () => {
            filterButtons.forEach((item) => item.classList.remove('active'));
            button.classList.add('active');
            activeCategory = button.dataset.category;
            filterCourses();
        });
    });
</script>
@endpush

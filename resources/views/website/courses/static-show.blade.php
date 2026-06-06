@extends('website.layout')

@section('title', $course['title'] . ' | SPI Learning')

@section('nav-search')
    <div class="chip-row">
        <a class="chip" href="{{ route('website.home') }}">
            <i class="fas fa-arrow-left" style="margin-right:8px"></i>
            វគ្គសិក្សាទាំងអស់
        </a>
        @foreach($relatedCourses as $relatedCourse)
            <a class="chip" href="{{ route('website.static-course.show', $relatedCourse['slug']) }}">{{ $relatedCourse['title'] }}</a>
        @endforeach
    </div>
@endsection

@section('content')
    <main class="page">
        <section class="course-shell">
            <aside class="module-sidebar">
                @foreach($modules as $module)
                    <div class="module-title">
                        <span class="module-number">{{ $module['number'] }}</span>
                        <span>{{ $module['title'] }}</span>
                        <i class="fas fa-chevron-right" style="margin-left:auto"></i>
                    </div>

                    <ul class="lesson-list">
                        @foreach($module['lessons'] as $lesson)
                            <li>
                                <a class="{{ $selectedLesson['slug'] === $lesson['slug'] ? 'active' : '' }}"
                                   href="{{ route('website.static-course.show', ['slug' => $course['slug'], 'lesson' => $lesson['slug']]) }}">
                                    <span class="lesson-dot"></span>
                                    <span>{{ $module['number'] }}.{{ $lesson['position'] }} {{ $lesson['title'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            </aside>

            <article class="lesson-content">
                <h1>{{ $selectedLesson['module_number'] }}. {{ $selectedLesson['title'] }}</h1>

                <div class="lesson-hero">
                    <div class="course-art {{ $course['style'] }}">
                        <div class="logo-mark">
                            <i class="{{ $course['icon'] }}"></i>
                            <b>{{ $course['code'] }}</b>
                        </div>
                    </div>
                </div>

                <div class="course-meta" style="margin-bottom:24px">
                    <span><i class="fas fa-layer-group"></i> {{ $selectedLesson['module_title'] }}</span>
                    <span><i class="fas fa-shapes"></i> Lesson</span>
                    <span><i class="far fa-clock"></i> 20 នាទី</span>
                </div>

                <div class="lesson-text">
                    <p>{{ $course['description'] }}</p>

                    <h2>{{ $selectedLesson['module_number'] }}.{{ $selectedLesson['position'] }}. សេចក្តីណែនាំ</h2>
                    <p>មេរៀននេះរៀបចំជាភាសាខ្មែរ ងាយស្រួលអាន និងផ្តោតលើការអនុវត្ត។ អ្នកអាចរៀនតាមលំដាប់នៅខាងឆ្វេង ដូចជារចនាប័ទ្ម Moodle។</p>

                    <h2>គោលបំណងមេរៀន</h2>
                    <p>បន្ទាប់ពីរៀនចប់ អ្នកនឹងយល់ពីគំនិតសំខាន់ៗ អាចសរសេរកូដឧទាហរណ៍តូចៗ និងមានមូលដ្ឋានសម្រាប់បន្តទៅមេរៀនបន្ទាប់។</p>

                    <h2>លំហាត់អនុវត្ត</h2>
                    <p>សូមបង្កើតឯកសារថ្មី សរសេរឧទាហរណ៍តាមមេរៀន ហើយកត់ត្រាសំណួរដែលអ្នកមិនទាន់យល់នៅផ្នែកមតិយោបល់ខាងក្រោម។</p>
                </div>

                <section class="comments">
                    <h2>សំណួរ និងមតិយោបល់</h2>
                    <div class="comment">
                        <span class="avatar-dot">គ</span>
                        <div>
                            <strong>គ្រូបង្រៀន</strong>
                            <p>សួស្តី! សូមផ្ញើសំណួររបស់អ្នក។ ខ្ញុំនឹងជួយពន្យល់ជាជំហានៗ។</p>
                        </div>
                    </div>
                    <div class="comment">
                        <span class="avatar-dot">ស</span>
                        <div>
                            <strong>សិស្ស</strong>
                            <p>មេរៀននេះងាយយល់ ប៉ុន្តែខ្ញុំចង់បានលំហាត់បន្ថែម។</p>
                        </div>
                    </div>
                    <form class="comment-form">
                        <textarea placeholder="សរសេរសំណួរ ឬមតិយោបល់របស់អ្នក..."></textarea>
                        <button class="primary-action" type="button">បញ្ចូលមតិយោបល់</button>
                    </form>
                </section>
            </article>
        </section>
    </main>
@endsection

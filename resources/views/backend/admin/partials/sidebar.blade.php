<aside class="main-sidebar sidebar-dark-primary sidebar-fixed">

    @php
        $sidebarAvatar = Auth::user()?->avatar
            ? (str_starts_with(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar))
            : asset('backend/dist/img/user2-160x160.jpg');
    @endphp

    <a href="#" class="brand-link shadow-sm">
        <img src="{{ asset('backend/dist/img/spilogo.png') }}" class="brand-image-custom" alt="Saint Paul Institute">
        <span class="brand-text font-weight-light">ប្រព័ន្ធគ្រប់គ្រប់កម្មវិធីសិក្សា</span>
    </a>

    <div class="sidebar">
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                {{-- User Panel --}}
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ $sidebarAvatar }}"
                            class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">
                            សួស្តី : {{ Auth::user()->username ?? 'User' }}
                        </a>
                    </div>
                </div>

                {{-- Dashboard --}}
                @can('dashboard.access')
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>ផ្ទាំងគ្រប់គ្រង</p>
                    </a>
                </li>
                @endcan

                {{-- ================= ACADEMIC STRUCTURE ================= --}}
                @can('academic.manage')
                <li class="nav-header text-uppercase">រចនាសម្ព័ន្ធសិក្សា</li>

                <li class="nav-item">
                    <a href="{{ route('admin.faculty.index') }}" class="nav-link {{ request()->is('faculty*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>មហាវិទ្យាល័យ</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.departments.index') }}" class="nav-link {{ request()->is('departments*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-sitemap"></i>
                        <p>ដេប៉ាតឺម៉ង់</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.academic-years.index') }}" class="nav-link {{ request()->is('admin/academic-years*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>ឆ្នាំសិក្សា</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.academic-progression.index') }}" class="nav-link {{ request()->is('admin/academic-progression*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-project-diagram"></i>
                        <p>ការបន្តសិក្សា</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.semesters.index') }}" class="nav-link {{ request()->is('admin/semesters*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>ឆមាស</p>
                    </a>
                </li>
                @endcan

                {{-- ================= COURSES ================= --}}
                @canany(['courses.view', 'courses.manage'])
                <li class="nav-header text-uppercase">វគ្គសិក្សា</li>

                <li class="nav-item has-treeview {{ request()->is('admin/courses*') || request()->is('admin/course-categories*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-graduation-cap"></i>
                        <p>
                            គ្រប់គ្រងវគ្គសិក្សា
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @can('courses.manage')
                        <li class="nav-item">
                            <a href="{{ route('admin.course-categories.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>ប្រភេទវគ្គសិក្សា</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.courses.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>បង្កើតវគ្គសិក្សា</p>
                            </a>
                        </li>
                        @endcan

                        @can('courses.view')
                        <li class="nav-item">
                            <a href="{{ route('admin.courses.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>បញ្ជីវគ្គសិក្សា</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                {{-- ================= LEARNING MODULE ================= --}}
                @canany(['lesson_contents.view', 'lesson_contents.manage'])
                <li class="nav-header text-uppercase">ប្រព័ន្ធសិក្សា</li>

                @can('lesson_contents.view')
                <li class="nav-item">
                    <a href="{{ route('admin.lesson-contents.index') }}" class="nav-link {{ request()->is('admin/lesson-contents*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>មេរៀន (Lessons)</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.lesson-videos.index') }}" class="nav-link {{ request()->is('admin/lesson-videos*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-video"></i>
                        <p>វីដេអូសិក្សា</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.lesson-documents.index') }}" class="nav-link {{ request()->is('admin/lesson-documents*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>ឯកសារសិក្សា</p>
                    </a>
                </li>
                @endcan

                @can('lesson_contents.manage')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>ការប្រឡង / Quiz</p>
                    </a>
                </li>
                @endcan
                @endcanany

                @can('grades.manage')
                <li class="nav-item">
                    <a href="{{ route('admin.course-grades.index') }}" class="nav-link {{ request()->is('admin/course-grades*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-star-half-alt"></i>
                        <p>ពិន្ទុសិស្ស</p>
                    </a>
                </li>
                @endcan

                @can('announcements.view')
                <li class="nav-item">
                    <a href="{{ route('admin.announcements.index') }}" class="nav-link {{ request()->is('admin/announcements*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>សេចក្តីជូនដំណឹង</p>
                    </a>
                </li>
                @endcan

                @can('learning_issues.view')
                <li class="nav-item">
                    <a href="{{ route('admin.learning-issues.index') }}" class="nav-link {{ request()->is('admin/learning-issues*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-life-ring"></i>
                        <p>បញ្ហាសិក្សា</p>
                    </a>
                </li>
                @endcan

                {{-- ================= PEOPLE ================= --}}
                @canany(['users.manage', 'students.view', 'students.manage', 'teachers.view', 'teachers.manage'])
                <li class="nav-header text-uppercase">គ្រប់គ្រងមនុស្ស</li>

                @can('users.manage')
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>អ្នកប្រើប្រាស់ និងតួនាទី</p>
                    </a>
                </li>
                @endcan

                @canany(['students.view', 'students.manage'])
                <li class="nav-item">
                    <a href="{{ route('admin.students.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>សិស្ស</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.student-enrollments.index') }}" class="nav-link {{ request()->is('admin/student-enrollments*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>Student Enrollment</p>
                    </a>
                </li>
                @endcanany

                @canany(['teachers.view', 'teachers.manage'])
                <li class="nav-item">
                    <a href="{{ route('admin.teachers.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>គ្រូបង្រៀន</p>
                    </a>
                </li>
                @endcanany
                @endcanany

                @can('certificates.manage')
                <li class="nav-item">
                    <a href="{{ route('admin.certificate-requests.index') }}" class="nav-link {{ request()->is('admin/certificate-requests*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-certificate"></i>
                        <p>សំណើវិញ្ញាបនបត្រ</p>
                    </a>
                </li>
                @endcan

                @can('activity_logs.view')
                <li class="nav-item">
                    <a href="{{ route('admin.activity-logs.index') }}" class="nav-link {{ request()->is('admin/activity-logs*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <p>កំណត់ត្រាសកម្មភាព</p>
                    </a>
                </li>
                @endcan

                {{-- ================= COMMUNICATION ================= --}}
                @can('dashboard.access')
                <li class="nav-header text-uppercase">ទំនាក់ទំនង</li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>សារប្រព័ន្ធ (Chat)</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>ប្រកាស / សេចក្តីជូនដំណឹង</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>អ៊ីមែលប្រព័ន្ធ</p>
                    </a>
                </li>
                @endcan

            </ul>
        </nav>
    </div>
</aside>

<aside class="main-sidebar sidebar-dark-primary sidebar-fixed">

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
                        <img src="{{ Auth::user()->avatar ?? asset('backend/dist/img/user2-160x160.jpg') }}"
                            class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">
                            សួស្តី : {{ Auth::user()->username ?? 'User' }}
                        </a>
                    </div>
                </div>

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>ផ្ទាំងគ្រប់គ្រង</p>
                    </a>
                </li>

                {{-- ================= ACADEMIC STRUCTURE ================= --}}
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

                {{-- ================= COURSES ================= --}}
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

                        <li class="nav-item">
                            <a href="{{ route('admin.courses.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>បញ្ជីវគ្គសិក្សា</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ================= LEARNING MODULE ================= --}}
                <li class="nav-header text-uppercase">ប្រព័ន្ធសិក្សា</li>

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

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>ការប្រឡង / Quiz</p>
                    </a>
                </li>

                {{-- ================= PEOPLE ================= --}}
                <li class="nav-header text-uppercase">គ្រប់គ្រងមនុស្ស</li>

                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>អ្នកប្រើប្រាស់ និងតួនាទី</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.students.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>សិស្ស</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.teachers.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>គ្រូបង្រៀន</p>
                    </a>
                </li>

                {{-- ================= COMMUNICATION ================= --}}
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

            </ul>
        </nav>
    </div>
</aside>

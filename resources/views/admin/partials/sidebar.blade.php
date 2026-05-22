<aside class="main-sidebar sidebar-dark-primary sidebar-fixed">

    <a href="#" class="brand-link shadow-sm">
        <img src="{{ asset('backend/dist/img/spilogo.png') }}" class="brand-image-custom" alt="Saint Paul Institute">
        <span class="brand-text font-weight-light">Saint Paul Institute</span>
    </a>

    <div class="sidebar">
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                {{-- User Panel --}}
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ Auth::user()->avatar ?? asset('backend/dist/img/user2-160x160.jpg') }}"
                            class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">Welcome : {{ Auth::user()->username ?? 'User' }}</a>
                    </div>
                </div>

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>University Dashboard</p>
                    </a>
                </li>
                {{-- ================= ADMIN FULL CONTROL ================= --}}


                <li class="nav-header text-uppercase">Academic Structure</li>
                 {{-- Faculty --}}
                <li class="nav-item has-treeview {{ request()->is('faculty*') ? 'menu-open' : '' }}">
                    <a href="{{ route('admin.faculty.index') }}"
                        class="nav-link {{ request()->is('faculty*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>
                            Faculty
                        </p>
                    </a>
                </li>

                {{-- Departments --}}
                <li class="nav-item has-treeview {{ request()->is('departments*') ? 'menu-open' : '' }}">
                    <a href="{{ route('admin.departments.index') }}"
                        class="nav-link {{ request()->is('departments*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>
                            Departments
                        </p>
                    </a>
                </li>

                {{-- Academic Years --}}
                <li class="nav-item has-treeview {{ request()->is('admin/academic-years*') ? 'menu-open' : '' }}">
                    <a href="{{ route('admin.academic-years.index') }}"
                        class="nav-link {{ request()->is('admin/academic-years*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                            Academic Years
                        </p>
                    </a>
                </li>

                {{-- Semesters --}}
                <li class="nav-item has-treeview {{ request()->is('admin/semesters*') ? 'menu-open' : '' }}">
                    <a href="{{ route('admin.semesters.index') }}"
                        class="nav-link {{ request()->is('admin/semesters*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>
                            Semesters
                        </p>
                    </a>
                </li>

                {{-- Courses --}}
                <li class="nav-item has-treeview {{ request()->is('admin/courses*') || request()->is('admin/course-categories*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/courses*') || request()->is('admin/course-categories*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-graduation-cap"></i>
                        <p>
                            គ្រប់គ្រង Course
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.course-categories.index') }}"
                                class="nav-link {{ request()->routeIs('admin.course-categories.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>ប្រភេទ Course</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.courses.create') }}"
                                class="nav-link {{ request()->routeIs('admin.courses.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>បង្កើត Course</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.courses.index') }}"
                                class="nav-link {{ request()->routeIs('admin.courses.index') || request()->routeIs('admin.courses.edit') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>បញ្ជី Course</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Subjects --}}
                <li class="nav-item has-treeview {{ request()->is('admin/subjects*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/subjects*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Subjects
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link {{ request()->routeIs('subject.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create Subject</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#"
                                class="nav-link {{ request()->routeIs('subject.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Subject List</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Lesson Content --}}
                <li class="nav-item has-treeview {{ request()->is('lessons*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('lessons*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Lesson Content
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link {{ request()->routeIs('lessons.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create Lesson</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#"
                                class="nav-link {{ request()->routeIs('lessons.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Lesson List</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>Sections / Classes</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Timetable</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase">People Management</li>

                {{-- Users --}}
                <li class="nav-item has-treeview {{ request()->is('admin/users*') ? 'menu-open' : '' }}">
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                            អ្នកប្រើ និងតួនាទី
                        </p>
                    </a>
                </li>

                {{-- Faculty Members --}}
                <li class="nav-item has-treeview {{ request()->is('teacher*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('teacher*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>
                            Faculty Members
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link {{ request()->routeIs('teacher.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Faculty</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#"
                                class="nav-link {{ request()->routeIs('teacher.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Faculty List</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Students --}}
                <li class="nav-item has-treeview {{ request()->is('student*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('student*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>
                            Students
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link {{ request()->routeIs('students.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Student</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#"
                                class="nav-link {{ request()->routeIs('students.list') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Student List</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>

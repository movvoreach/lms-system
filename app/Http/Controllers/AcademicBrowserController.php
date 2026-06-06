<?php

namespace App\Http\Controllers;

use App\Models\CertificateRequest;
use App\Models\Course;
use App\Models\Department;
use App\Models\Student;
use Illuminate\Http\Request;

class AcademicBrowserController extends Controller
{
    public function departmentCourses(int $department)
    {
        $department = Department::query()
            ->with([
                'faculty',
                'courses' => fn ($query) => $query->with(['category', 'semester.academicYear', 'studentRegistrations'])
                    ->orderByDesc('created_at'),
            ])
            ->findOrFail($department);

        return view('academic-browser.department-courses', compact('department'));
    }

    public function courseStudents(Request $request, int $course)
    {
        $course = Course::query()
            ->with([
                'department.faculty',
                'category',
                'semester.academicYear',
                'lessonContents' => fn ($query) => $query->orderBy('module_number')->orderBy('position'),
                'teacherAssignments.teacher.user',
                'studentRegistrations.student.user',
                'studentRegistrations.student.lessonGrades' => fn ($query) => $query->where('course_id', $course),
                'studentRegistrations.academicYear',
            ])
            ->findOrFail($course);

        $selectedLessonId = (int) ($request->integer('lesson_content_id') ?: optional($course->lessonContents->first())->id);
        $selectedLesson = $course->lessonContents->firstWhere('id', $selectedLessonId);

        $registrations = $course->studentRegistrations
            ->sortBy(fn ($registration) => $registration->student?->student_number)
            ->values();
        $requiredLessons = $course->lessonContents->where('completion_required', true);

        if ($requiredLessons->isEmpty()) {
            $requiredLessons = $course->lessonContents->whereNotNull('max_score');
        }

        $completionByRegistration = $registrations->mapWithKeys(function ($registration) use ($requiredLessons) {
            $passedLessonIds = $registration->student?->lessonGrades
                ?->where('passed', true)
                ->pluck('lesson_content_id') ?? collect();

            $passed = $requiredLessons->whereIn('id', $passedLessonIds)->count();

            return [$registration->registration_id => [
                'required' => $requiredLessons->count(),
                'passed' => $passed,
                'completed' => $requiredLessons->isNotEmpty() && $passed >= $requiredLessons->count(),
            ]];
        });
        $allStudentsCompleted = $registrations->isNotEmpty()
            && $completionByRegistration->every(fn (array $completion) => $completion['completed']);
        $certificateRequests = CertificateRequest::query()
            ->where('course_id', $course->id)
            ->whereIn('student_id', $registrations->pluck('student_id'))
            ->get();
        $pendingCertificateCount = $certificateRequests->where('status', 'pending')->count();
        $approvedCertificateCount = $certificateRequests->where('status', 'approved')->count();
        $rejectedCertificateCount = $certificateRequests->where('status', 'rejected')->count();
        $missingCertificateRequestsCount = $registrations
            ->whereNotIn('student_id', $certificateRequests->whereIn('status', ['pending', 'approved'])->pluck('student_id'))
            ->count();
        $certificateStatus = 'not_ready';

        if ($allStudentsCompleted && $approvedCertificateCount >= $registrations->count()) {
            $certificateStatus = 'done';
        } elseif ($allStudentsCompleted && $pendingCertificateCount > 0) {
            $certificateStatus = 'processing';
        } elseif ($allStudentsCompleted) {
            $certificateStatus = 'ready';
        } elseif ($rejectedCertificateCount > 0) {
            $certificateStatus = 'rejected';
        }

        return view('academic-browser.course-students', compact(
            'course',
            'registrations',
            'selectedLesson',
            'selectedLessonId',
            'completionByRegistration',
            'allStudentsCompleted',
            'missingCertificateRequestsCount',
            'pendingCertificateCount',
            'approvedCertificateCount',
            'certificateStatus'
        ));
    }

    public function studentDetail(int $course, int $student)
    {
        $course = Course::query()
            ->with(['department.faculty', 'category', 'semester.academicYear', 'lessonContents' => fn ($query) => $query->orderBy('module_number')->orderBy('position')])
            ->findOrFail($course);

        $student = Student::query()
            ->with([
                'user',
                'course.department.faculty',
                'course.semester.academicYear',
                'lessonGrades' => fn ($query) => $query->where('course_id', $course->id)->with('lessonContent'),
                'courseRegistrations.course.department.faculty',
                'courseRegistrations.course.semester.academicYear',
                'courseRegistrations.academicYear',
                'academicYearRecords.academicYear',
            ])
            ->whereHas('courseRegistrations', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->findOrFail($student);

        $registration = $student->courseRegistrations
            ->firstWhere('course_id', $course->id);
        $requiredLessons = $course->lessonContents->where('completion_required', true);

        if ($requiredLessons->isEmpty()) {
            $requiredLessons = $course->lessonContents->whereNotNull('max_score');
        }

        $passedLessonIds = $student->lessonGrades
            ->where('passed', true)
            ->pluck('lesson_content_id');
        $completion = [
            'required' => $requiredLessons->count(),
            'passed' => $requiredLessons->whereIn('id', $passedLessonIds)->count(),
        ];
        $completion['completed'] = $completion['required'] > 0 && $completion['passed'] >= $completion['required'];

        return view('academic-browser.student-detail', compact('course', 'student', 'registration', 'completion', 'requiredLessons'));
    }
}

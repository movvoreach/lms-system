<?php

namespace App\Http\Controllers;

use App\Models\CertificateRequest;
use App\Models\Course;
use App\Models\LessonContent;
use App\Models\StudentCourseRegistration;
use App\Models\StudentLessonGrade;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseGradeController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Teacher::query()->where('user_id', $request->user()->user_id)->first();
        $isAdminUser = $request->user()->hasAnyRole(['Administrator', 'Admin', 'Manager']);

        $courses = Course::query()
            ->with(['lessonContents' => fn ($query) => $query->orderBy('module_number')->orderBy('position')])
            ->when(! $isAdminUser, function ($query) use ($teacher) {
                $query->whereHas('teacherAssignments', function ($query) use ($teacher) {
                    $query->where('teacher_id', $teacher?->teacher_id ?? 0);
                });
            })
            ->orderBy('title')
            ->get();

        $selectedCourseId = (int) ($request->integer('course_id') ?: optional($courses->first())->id);
        $selectedCourse = $courses->firstWhere('id', $selectedCourseId);
        $courseDetails = $selectedCourse?->lessonContents ?? collect();
        $selectedLessonId = (int) ($request->integer('lesson_content_id') ?: optional($courseDetails->first())->id);
        $selectedLesson = $courseDetails->firstWhere('id', $selectedLessonId);

        $registrations = StudentCourseRegistration::query()
            ->with([
                'academicYear',
                'student.user',
                'student.lessonGrades' => fn ($query) => $query->when($selectedLessonId, function ($query) use ($selectedLessonId) {
                    $query->where('lesson_content_id', $selectedLessonId);
                }),
                'course.lessonContents',
                'course.certificateRequests',
                'course.semester.academicYear',
            ])
            ->when($selectedCourseId, function ($query) use ($selectedCourseId) {
                $query->where('course_id', $selectedCourseId);
            })
            ->when(! $isAdminUser, function ($query) use ($teacher) {
                $query->whereHas('course.teacherAssignments', function ($query) use ($teacher) {
                    $query->where('teacher_id', $teacher?->teacher_id ?? 0);
                });
            })
            ->latest('registration_id')
            ->get();

        return view('course-grade.index', compact(
            'registrations',
            'courses',
            'courseDetails',
            'selectedCourse',
            'selectedCourseId',
            'selectedLesson',
            'selectedLessonId'
        ));
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'lesson_content_id' => ['required', 'integer', 'exists:lesson_contents,id'],
            'grades' => ['required', 'array'],
            'grades.*.score' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'grades.*.feedback' => ['nullable', 'string'],
            'stay_on_page' => ['nullable', 'boolean'],
        ]);

        $lesson = LessonContent::query()
            ->where('course_id', $validated['course_id'])
            ->findOrFail($validated['lesson_content_id']);

        $registrations = StudentCourseRegistration::query()
            ->with('course.lessonContents')
            ->where('course_id', $validated['course_id'])
            ->whereIn('registration_id', array_keys($validated['grades']))
            ->get()
            ->keyBy('registration_id');

        foreach ($registrations as $registration) {
            $this->ensureCanGrade($request, $registration);
        }

        $teacher = Teacher::query()->where('user_id', $request->user()->user_id)->first();

        DB::transaction(function () use ($validated, $lesson, $registrations, $teacher): void {
            foreach ($validated['grades'] as $registrationId => $gradeData) {
                $registration = $registrations->get((int) $registrationId);

                if (! $registration) {
                    continue;
                }

                $score = $gradeData['score'] ?? null;
                $passingScore = $lesson->passing_score ?? $lesson->max_score ?? 0;

                StudentLessonGrade::query()->updateOrCreate(
                    [
                        'student_id' => $registration->student_id,
                        'lesson_content_id' => $lesson->id,
                    ],
                    [
                        'course_id' => $registration->course_id,
                        'teacher_id' => $teacher?->teacher_id,
                        'score' => $score,
                        'max_score' => $lesson->max_score,
                        'passed' => filled($score) && (float) $score >= (float) $passingScore,
                        'feedback' => $gradeData['feedback'] ?? null,
                        'graded_at' => now(),
                    ]
                );

                $completion = $this->completionSummary($registration->fresh(['course.lessonContents']));

                if ($completion['completed']) {
                    $registration->update([
                        'status' => 'completed',
                        'completed_at' => $registration->completed_at ?? now(),
                    ]);
                }
            }
        });

        if ($request->boolean('stay_on_page')) {
            return back()->with('success', 'Grades saved successfully.');
        }

        return redirect()
            ->route('admin.course-grades.index', [
                'course_id' => $validated['course_id'],
                'lesson_content_id' => $validated['lesson_content_id'],
            ])
            ->with('success', 'Grades saved successfully.');
    }

    public function edit(Request $request, int $registration)
    {
        $registration = StudentCourseRegistration::query()
            ->with(['student.user', 'course.lessonContents' => fn ($query) => $query->orderBy('module_number')->orderBy('position')])
            ->findOrFail($registration);

        $this->ensureCanGrade($request, $registration);

        return redirect()
            ->route('admin.courses.students.index', $registration->course_id)
            ->with('info', 'Please input scores from the course students page.');
    }

    public function update(Request $request, int $registration)
    {
        $registration = StudentCourseRegistration::query()->with('course.lessonContents')->findOrFail($registration);
        $this->ensureCanGrade($request, $registration);

        $validated = $request->validate([
            'grades' => ['required', 'array'],
            'grades.*.score' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'grades.*.feedback' => ['nullable', 'string'],
        ]);

        $teacher = Teacher::query()->where('user_id', $request->user()->user_id)->first();
        $lessons = $registration->course->lessonContents->keyBy('id');

        DB::transaction(function () use ($validated, $registration, $teacher, $lessons): void {
            foreach ($validated['grades'] as $lessonId => $gradeData) {
                $lesson = $lessons->get((int) $lessonId);

                if (! $lesson) {
                    continue;
                }

                $score = $gradeData['score'] ?? null;
                $passingScore = $lesson->passing_score ?? $lesson->max_score ?? 0;

                StudentLessonGrade::query()->updateOrCreate(
                    [
                        'student_id' => $registration->student_id,
                        'lesson_content_id' => $lesson->id,
                    ],
                    [
                        'course_id' => $registration->course_id,
                        'teacher_id' => $teacher?->teacher_id,
                        'score' => $score,
                        'max_score' => $lesson->max_score,
                        'passed' => filled($score) && (float) $score >= (float) $passingScore,
                        'feedback' => $gradeData['feedback'] ?? null,
                        'graded_at' => now(),
                    ]
                );
            }

            $completion = $this->completionSummary($registration->fresh(['course.lessonContents']));

            if ($completion['completed']) {
                $registration->update([
                    'status' => 'completed',
                    'completed_at' => $registration->completed_at ?? now(),
                ]);
            }
        });

        return redirect()
            ->route('admin.courses.students.index', $registration->course_id)
            ->with('success', 'Grades saved successfully.');
    }

    public function requestCertificate(Request $request, int $registration)
    {
        $registration = StudentCourseRegistration::query()->with('course.lessonContents')->findOrFail($registration);
        $this->ensureCanGrade($request, $registration);

        $completion = $this->completionSummary($registration);

        if (! $completion['completed']) {
            return redirect()
                ->route('admin.courses.students.index', $registration->course_id)
                ->with('error', 'Certificate can be requested only after all required lessons are passed.');
        }

        $teacher = Teacher::query()->where('user_id', $request->user()->user_id)->first();
        $validated = $request->validate(['teacher_note' => ['nullable', 'string']]);

        CertificateRequest::query()->updateOrCreate(
            [
                'student_id' => $registration->student_id,
                'course_id' => $registration->course_id,
                'status' => 'pending',
            ],
            [
                'registration_id' => $registration->registration_id,
                'requested_by_teacher_id' => $teacher?->teacher_id,
                'teacher_note' => $validated['teacher_note'] ?? null,
                'requested_at' => now(),
            ]
        );

        return redirect()
            ->route('admin.courses.students.index', $registration->course_id)
            ->with('success', 'Certificate request sent to admin.');
    }

    public function requestCourseCertificates(Request $request)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ]);

        $registrations = StudentCourseRegistration::query()
            ->with('course.lessonContents')
            ->where('course_id', $validated['course_id'])
            ->get();

        if ($registrations->isEmpty()) {
            return redirect()
                ->route('admin.courses.students.index', $validated['course_id'])
                ->with('error', 'No students found for this course.');
        }

        foreach ($registrations as $registration) {
            $this->ensureCanGrade($request, $registration);
        }

        $allCompleted = $registrations->every(function (StudentCourseRegistration $registration) {
            return $this->completionSummary($registration)['completed'];
        });

        if (! $allCompleted) {
            return redirect()
                ->route('admin.courses.students.index', $validated['course_id'])
                ->with('error', 'All students must complete the required lessons before requesting certificates.');
        }

        $teacher = Teacher::query()->where('user_id', $request->user()->user_id)->first();
        $existingStudentIds = CertificateRequest::query()
            ->where('course_id', $validated['course_id'])
            ->whereIn('student_id', $registrations->pluck('student_id'))
            ->whereIn('status', ['pending', 'approved'])
            ->pluck('student_id');
        $created = 0;

        DB::transaction(function () use ($registrations, $existingStudentIds, $teacher, &$created): void {
            foreach ($registrations as $registration) {
                if ($existingStudentIds->contains($registration->student_id)) {
                    continue;
                }

                CertificateRequest::query()->create([
                    'student_id' => $registration->student_id,
                    'course_id' => $registration->course_id,
                    'registration_id' => $registration->registration_id,
                    'requested_by_teacher_id' => $teacher?->teacher_id,
                    'status' => 'pending',
                    'teacher_note' => 'Course completed. Waiting for admin approval.',
                    'requested_at' => now(),
                ]);

                $created++;
            }
        });

        return redirect()
            ->route('admin.courses.students.index', $validated['course_id'])
            ->with('success', $created > 0
                ? "Certificate requests sent for {$created} students. Status: processing, waiting for admin approval."
                : 'Certificate requests are already processing or approved for all completed students.');
    }

    private function ensureCanGrade(Request $request, StudentCourseRegistration $registration): void
    {
        if ($request->user()->hasAnyRole(['Administrator', 'Admin', 'Manager'])) {
            return;
        }

        $teacher = Teacher::query()->where('user_id', $request->user()->user_id)->first();

        abort_unless($teacher && $teacher->courseAssignments()
            ->where('course_id', $registration->course_id)
            ->exists(), 403);
    }

    private function completionSummary(StudentCourseRegistration $registration): array
    {
        $requiredLessons = LessonContent::query()
            ->where('course_id', $registration->course_id)
            ->where('completion_required', true)
            ->get();

        if ($requiredLessons->isEmpty()) {
            $requiredLessons = LessonContent::query()
                ->where('course_id', $registration->course_id)
                ->whereNotNull('max_score')
                ->get();
        }

        $passedCount = StudentLessonGrade::query()
            ->where('student_id', $registration->student_id)
            ->where('course_id', $registration->course_id)
            ->whereIn('lesson_content_id', $requiredLessons->pluck('id'))
            ->where('passed', true)
            ->count();

        return [
            'required' => $requiredLessons->count(),
            'passed' => $passedCount,
            'completed' => $requiredLessons->isNotEmpty() && $passedCount >= $requiredLessons->count(),
        ];
    }
}

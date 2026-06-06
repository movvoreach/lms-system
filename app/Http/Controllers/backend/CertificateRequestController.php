<?php

namespace App\Http\Controllers\backend;

use App\Models\CertificateRequest;
use App\Models\Course;
use Illuminate\Http\Request;

class CertificateRequestController extends Controller
{
    public function index(Request $request)
    {
        $courseId = $request->integer('course_id');
        $course = $courseId ? Course::query()->find($courseId) : null;

        $certificateRequests = CertificateRequest::query()
            ->with(['student.user', 'course', 'requestedByTeacher.user', 'reviewedByUser'])
            ->when($courseId, fn ($query) => $query->where('course_id', $courseId))
            ->latest('certificate_request_id')
            ->get();

        return view('certificate-request.index', compact('certificateRequests', 'course'));
    }

    public function update(Request $request, int $certificateRequest)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'admin_note' => ['nullable', 'string'],
        ]);

        $certificateRequest = CertificateRequest::query()->findOrFail($certificateRequest);

        $certificateRequest->update([
            'status' => $validated['status'],
            'admin_note' => $validated['admin_note'] ?? null,
            'reviewed_by_user_id' => $request->user()->user_id,
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Certificate request updated successfully.');
    }
}



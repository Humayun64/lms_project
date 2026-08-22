<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Batch;
use App\Models\OfflineRegistration;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    // Manage batches + registrations for an offline course
    public function index(Course $course)
    {
        $course->load('batches.registrations');
        $registrations = OfflineRegistration::where('course_id', $course->id)
            ->with('batch')->latest()->get();

        return view('admin.courses.batches', compact('course', 'registrations'));
    }

    // Set how students pay for this offline course
    public function updatePaymentOption(Request $request, Course $course)
    {
        $request->validate(['offline_payment' => ['required', 'in:in_person,online,both']]);
        $course->update(['offline_payment' => $request->offline_payment]);

        return back()->with('success', 'Payment option updated.');
    }

    /* ---------------- Batches ---------------- */

    public function storeBatch(Request $request, Course $course)
    {
        $data = $this->validateBatch($request);
        $course->batches()->create($data);

        return back()->with('success', 'Batch added.');
    }

    public function updateBatch(Request $request, Batch $batch)
    {
        $data = $this->validateBatch($request);
        $data['status'] = $request->input('status', $batch->status);
        $batch->update($data);

        return back()->with('success', 'Batch updated.');
    }

    public function destroyBatch(Batch $batch)
    {
        $batch->delete();
        return back()->with('success', 'Batch deleted.');
    }

    private function validateBatch(Request $request): array
    {
        return $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'schedule'   => ['nullable', 'string', 'max:255'],
            'venue'      => ['nullable', 'string', 'max:255'],
            'seats'      => ['nullable', 'integer', 'min:1'],
            'status'     => ['nullable', 'in:open,closed'],
        ]);
    }

    /* ---------------- Registrations ---------------- */

    public function updateRegistration(Request $request, OfflineRegistration $registration)
    {
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
            'paid'   => ['nullable', 'boolean'],
        ]);

        $registration->update([
            'status' => $request->status,
            'paid'   => $request->boolean('paid'),
        ]);

        return back()->with('success', 'Registration updated.');
    }

    public function destroyRegistration(OfflineRegistration $registration)
    {
        $registration->delete();
        return back()->with('success', 'Registration deleted.');
    }
}

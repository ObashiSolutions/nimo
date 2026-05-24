<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use App\Models\ApplicantActivity;
use App\Models\ApplicantTimeline;
use App\Models\ApplicantStatusHistory;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;

class StaffApplicationController extends Controller
{
    public function updateStatus(Request $request, Applicant $applicant)
    {
        $currentStaffUser = Auth::guard('staff')->user();

        if ($currentStaffUser->role === 'support') {
            return back()->withErrors('Support users cannot change application status.');
        }

        if (
            $currentStaffUser->role === 'reviewer'
            && (int) $applicant->assigned_staff_user_id !== (int) $currentStaffUser->id
        ) {
            return back()->withErrors('Reviewers can only update applications assigned to them.');
        }

        $validated = $request->validate([
            'application_status' => 'required|string|in:In Review,Approved,Rejected,Closed',
        ]);

        if (
            $currentStaffUser->role === 'reviewer'
            && in_array($validated['application_status'], ['Approved', 'Closed'])
        ) {
            return back()->withErrors('Reviewers cannot approve or close applications.');
        }

        $oldStatus = $applicant->application_status;

        $applicant->update([
            'application_status' => $validated['application_status'],
        ]);

        ApplicantStatusHistory::create([
            'applicant_id' => $applicant->id,
            'old_status' => $oldStatus,
            'new_status' => $validated['application_status'],
            'changed_by' => $currentStaffUser?->first_name,
        ]);

        ApplicantTimeline::create([
            'applicant_id' => $applicant->id,
            'event_type' => 'status_update',
            'message' => 'Application status updated to: ' . $validated['application_status'],
            'performed_by' => $currentStaffUser?->first_name,
        ]);

        ApplicantActivity::create([
            'applicant_id' => $applicant->id,
            'activity_type' => 'Status Change',
            'description' =>
                'Application status changed from "' .
                $oldStatus .
                '" to "' .
                $validated['application_status'] .
                '".',
            'performed_by' => $currentStaffUser?->first_name ?? 'Staff',
        ]);

        return back()->with('success_message', 'Application status updated.');
    }

    public function updatePaymentStatus(Request $request, Applicant $applicant)
    {
        $currentStaffUser = Auth::guard('staff')->user();

        if (!in_array($currentStaffUser?->role, ['admin', 'manager'])) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_status' => 'required|string|in:Unpaid,Pending Verification,Paid',
        ]);

        $oldStatus = $applicant->payment_status;
        $newStatus = $validated['payment_status'];
        $updates = [
            'payment_status' => $newStatus,
        ];

        if ($newStatus === 'Paid') {
            $updates['application_status'] = 'Payment Verified';
            $updates['payment_submitted_at'] = $applicant->payment_submitted_at ?? now();
        } elseif ($newStatus === 'Pending Verification') {
            $updates['application_status'] = 'Payment Receipt Submitted';
            $updates['payment_submitted_at'] = $applicant->payment_submitted_at ?? now();
        }

        $applicant->update($updates);

        $payment = $applicant->payments()
            ->where('provider', 'manual_transfer')
            ->latest()
            ->first();

        if ($payment) {
            $payment->update([
                'status' => match ($newStatus) {
                    'Paid' => 'success',
                    'Pending Verification' => 'pending_verification',
                    default => 'unpaid',
                },
            ]);
        }

        ApplicantTimeline::create([
            'applicant_id' => $applicant->id,
            'event_type' => 'payment_status_update',
            'message' => 'Payment status updated from "' . $oldStatus . '" to "' . $newStatus . '".',
            'performed_by' => $currentStaffUser?->first_name,
        ]);

        ApplicantActivity::create([
            'applicant_id' => $applicant->id,
            'activity_type' => 'Payment Status Change',
            'description' => 'Payment status changed from "' . $oldStatus . '" to "' . $newStatus . '".',
            'performed_by' => $currentStaffUser?->first_name ?? 'Staff',
        ]);

        return back()->with('success_message', 'Payment status updated.');
    }

    public function uploadReceipt(Request $request, Applicant $applicant)
    {
        $currentStaffUser = Auth::guard('staff')->user();

        if (!in_array($currentStaffUser?->role, ['admin', 'manager'])) {
            abort(403);
        }

        if ($applicant->receipt_path) {
            return back()->withErrors('This application already has a receipt.');
        }

        $request->validate([
            'payment_receipt' => 'required|mimes:jpg,jpeg,png,pdf|max:32768',
        ]);

        $receiptPath = $request->file('payment_receipt')
            ->store('payment_receipts', 'local');

        $applicant->update([
            'receipt_path' => $receiptPath,
            'payment_status' => 'Pending Verification',
            'application_status' => 'Payment Receipt Submitted',
            'payment_submitted_at' => now(),
        ]);

        Payment::create([
            'applicant_id' => $applicant->id,
            'provider' => 'manual_transfer',
            'reference' => 'STAFF-MANUAL-' . strtoupper(\Illuminate\Support\Str::random(10)),
            'amount' => (int) env('PAYSTACK_PAYMENT_AMOUNT', 20000000),
            'currency' => 'NGN',
            'status' => 'pending_verification',
            'provider_response' => [
                'receipt_uploaded_by_staff' => true,
                'staff_user_id' => $currentStaffUser?->id,
            ],
        ]);

        ApplicantTimeline::create([
            'applicant_id' => $applicant->id,
            'event_type' => 'payment_receipt_uploaded',
            'message' => 'Payment receipt uploaded by staff.',
            'performed_by' => $currentStaffUser?->first_name,
        ]);

        ApplicantActivity::create([
            'applicant_id' => $applicant->id,
            'activity_type' => 'Receipt Upload',
            'description' => 'Payment receipt uploaded by staff.',
            'performed_by' => $currentStaffUser?->first_name ?? 'Staff',
        ]);

        return back()->with('success_message', 'Payment receipt uploaded successfully.');
    }
}

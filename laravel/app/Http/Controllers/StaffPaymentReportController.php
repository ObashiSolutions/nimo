<?php

namespace App\Http\Controllers;

use App\Models\Payment;

class StaffPaymentReportController extends Controller
{
    public function index()
    {
        $status = request('status');
        $provider = request('provider');

        $query = Payment::with('applicant')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        if ($provider) {
            $query->where('provider', $provider);
        }

        $payments = $query
            ->paginate(100)
            ->withQueryString();

        return view('staff.payments.index', compact('payments'));
    }
}
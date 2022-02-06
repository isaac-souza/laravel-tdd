<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Mail\NewAppointment;

class AppointmentController extends Controller
{
    public function index()
    {
        return auth()->user()->appointments;
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'starts_at' => 'required|date|after:today',
            'duration' => ['required','numeric', Rule::in([15, 30, 45, 60])],
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $appointment = $user->appointments()->create($request->only(['patient_id', 'starts_at', 'duration']));

        /** @var \App\Models\Patient $patient */
        $patient = Patient::find($request->patient_id);
        Mail::to($patient)->send(new NewAppointment($appointment));

        return redirect()->route('appointments.index');
    }
}

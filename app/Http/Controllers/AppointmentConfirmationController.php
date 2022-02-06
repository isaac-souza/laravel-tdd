<?php

namespace App\Http\Controllers;

use App\Mail\AppointmentConfirmed;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Mail;

class AppointmentConfirmationController extends Controller
{
    public function store(Request $request, Appointment $appointment)
    {
        $appointment->update(['state' => 'confirmed']);

        Mail::to($appointment->user)->send(new AppointmentConfirmed($appointment));
    }
}

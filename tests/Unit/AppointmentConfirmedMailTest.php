<?php

namespace Tests\Unit;

use App\Mail\AppointmentConfirmed;
use Tests\TestCase;
use App\Models\Appointment;
use App\Mail\NewAppointment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentConfirmedMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_appointment_confirmed_has_the_start_time_duration_and_patient_name()
    {
        $appointment = Appointment::factory()->create();
        
        $mailable = new AppointmentConfirmed($appointment);
        
        $mailable->assertSeeInHtml($appointment->starts_at);
        $mailable->assertSeeInHtml($appointment->duration);
        $mailable->assertSeeInHtml($appointment->patient->name);
    }
}

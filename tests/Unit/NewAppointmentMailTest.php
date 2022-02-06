<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Appointment;
use App\Mail\NewAppointment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewAppointmentMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_appointment_email_has_a_link_to_confirm_the_appointment()
    {
        $appointment = Appointment::factory()->create();
        
        $mailable = new NewAppointment($appointment);
        
        $mailable->assertSeeInHtml(route('appointments.confirm', $appointment->id));
    }
}

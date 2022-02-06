<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;
use App\Mail\NewAppointment;
use App\Mail\AppointmentConfirmed;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_receives_and_email_when_an_appointment_is_created()
    {
        $this->withoutExceptionHandling();

        // Arrange
        Mail::fake();
        
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        // Pre assertion
        $this->assertDatabaseCount('appointments', 0);
        Mail::assertNothingSent();

        // Act
        /** @var \App\Models\User $user */
        $this->actingAs($user);
        $this->post(route('appointments.store', [
            'patient_id' => $patient->id,
            'starts_at' => Carbon::now()->addDays(3)->addHour()->toDateTimeString(),
            'duration' => 30,
        ]));

        // Assert
        $this->assertDatabaseCount('appointments', 1);
        Mail::assertSent(NewAppointment::class);
    }

    public function test_appointment_is_marked_as_confirmed_when_the_patient_access_the_confirmation_url()
    {
        $this->withoutExceptionHandling();

        // Arrange
        $appointment = Appointment::factory()->create();

        // Pre assertion
        $this->assertEquals('pending', $appointment->state);

        // Act
        $this->post(route('appointments.confirm', $appointment->id));
        
        // Assert
        $this->assertEquals('confirmed', $appointment->refresh()->state);
    }

    public function test_user_receives_an_confirmation_email_when_the_patient_confirms_the_appointment()
    {
        $this->withoutExceptionHandling();

        // Arrange
        Mail::fake();
        $appointment = Appointment::factory()->create();

        // Pre assertion
        $this->assertEquals('pending', $appointment->state);
        Mail::assertNothingSent();

        // Act
        $this->post(route('appointments.confirm', $appointment->id));

        // Assert
        Mail::assertSent(AppointmentConfirmed::class, function(AppointmentConfirmed $mail) use($appointment) {
            return $appointment->user->id == $mail->appointment->user->id && 
                $mail->appointment->id == $appointment->id;
        });
    }

    public function test_an_appointment_can_be_confirmed_by_guest_patient()
    {
        $this->withoutExceptionHandling();

        // Arrange
        $appointment = Appointment::factory()->create();

        // Pre assertion
        $this->assertEquals(null, auth()->user());
        
        // Act
        $this->post(route('appointments.confirm', $appointment->id));
        
        // Assert
        $this->assertEquals('confirmed', $appointment->refresh()->state);
    }

    public function test_only_authenticated_users_can_create_new_appointments()
    {
        // Arrange
        Mail::fake();

        // Pre assertion
        $this->assertDatabaseCount('appointments', 0);
        Mail::assertNothingSent();

        // Act
        $response = $this->post(route('appointments.store', [
            'patient_id' => 'garbage',
            'starts_at' => Carbon::now()->addDays(3)->addHour()->toDateTimeString(),
            'duration' => 30,
        ]));

        // Assert
        Mail::assertNothingSent();
        $this->assertDatabaseCount('appointments', 0);
        $response->assertRedirect('/login');
    }
}

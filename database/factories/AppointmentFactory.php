<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    public function definition()
    {
        $durations = [15, 30, 45, 60];

        return [
            'user_id' => User::factory(),
            'patient_id' => Patient::factory(),
            'starts_at' => Carbon::now()->addHour(),
            'duration' => $durations[random_int(0, 3)],
        ];
    }
}

<?php

namespace Tests\Feature\Appointment;

use App\Models\User;
use App\Models\Appointment;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppointmentCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_make_an_appointment()
    {
        $user = User::factory()->create();
        $studio = Studio::factory()->create();
        $artist = User::factory()->create();

		$appointment = [
			'date' => Carbon::now(),
			'studio_id' => $studio->id,
			'artist_id' => $artist->id,
		];

        $response = $this
            ->actingAs($user)
            ->post('/user/' . $user->id . 'appointment', $appointment);

        $response->assertStatus(201);
		$this->assertDatabaseHas('appointments', $appointment);


		$lastAppointment = Appointment::latest()->first();

		$this->assertEquals($appointment['date'], $lastAppointment->date);
		$this->assertEquals($appointment['studio_id'], $lastAppointment->studio_id);
		$this->assertEquals($appointment['artist_id'], $lastAppointment->artist_id);
    }
}

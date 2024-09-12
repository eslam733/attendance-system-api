<?php

namespace Tests\Feature;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AttendanceTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->user);
    }

    function test_check_in(): void
    {
        $response = $this->get(route('attendance.check-in'));

        $response->assertStatus(200);

        $attendance = Attendance::getLastCheckIn($this->user->id);

        $this->assertNull($attendance->check_out);
        $this->assertNull($attendance->duration);

        $this->assertEquals(Carbon::createFromDate($attendance->check_in)->day, Carbon::now()->day);

        $response = $this->get(route('attendance.check-in'));

        $response->assertStatus(409);
    }

    function test_check_out(): void
    {
        $this->test_check_in();

        $response = $this->put(route('attendance.check-out'));

        $response->assertStatus(200);

        $attendance = Attendance::first();

        $this->assertEquals(Carbon::createFromDate($attendance->check_out)->day, Carbon::now()->day);

        $checkInTime = Carbon::createFromDate($attendance->check_in);
        $checkOutTime = Carbon::createFromDate($attendance->check_out);

        $this->assertEquals($attendance->duration, $checkInTime->diffInMinutes($checkOutTime));

        $response = $this->put(route('attendance.check-out'));

        $response->assertStatus(409);
    }

}

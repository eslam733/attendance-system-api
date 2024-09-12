<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    function checkIn(Request $req)
    {
        $user = auth()->user();

        $hasOldCheckIn = Attendance::getLastCheckIn($user->id);

        if ($hasOldCheckIn) {
            return $this->errorResponse(errors: null, code: 409, message: 'you are already in check-in');
        }

        $currentTime = Carbon::now();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'check_in' => $currentTime,
        ]);

        if (empty($attendance)) {
            return $this->errorResponse(errors: null, code: 422, message: 'failed check-in');
        }

        return $this->successResponse(data: null, message: "check-in has been successful at $currentTime");
    }

    function checkOut(Request $req)
    {
        $user = auth()->user();

        $oldCheckIn = Attendance::getLastCheckIn($user->id);

        if (empty($oldCheckIn)) {
            return $this->errorResponse(errors: null, code: 409, message: 'You aren\'t checking in');
        }

        $checkInTime = Carbon::parse($oldCheckIn->check_in);

        $checkOutTime = Carbon::now();

        // This loop handles cases where the check-in and check-out dates are on different days
        while (!$checkInTime->isSameDay($checkOutTime) && $checkInTime->lessThan($checkOutTime)) {
            $checkOutForThisDay = clone($checkInTime);

            $checkOutForThisDay->hour(23)->minute(59)->second(59);

            Attendance::updateOrCreate([
                'user_id' => $user->id,
                'check_in' => $checkInTime,
            ], [
                'check_out' => $checkOutForThisDay,
                'duration' => $checkInTime->diffInMinutes($checkOutForThisDay),
            ]);

            $checkInTime->addDay()->hour(0)->minute(0)->second(0);
        }

        $duration = Carbon::parse($checkInTime)->diffInMinutes($checkOutTime);

        Attendance::updateOrCreate([
            'user_id' => $user->id,
            'check_in' => $checkInTime,
        ], [
            'check_out' => $checkOutTime,
            'duration' => $duration,
        ]);

        return $this->successResponse(data: null, message: "check-out has been successful at $checkOutTime");
    }

    function getTotalHours(Request $req)
    {
        $req->validate([
            'from' => 'required|date_format:Y-m-d',
            'to' => 'required|date_format:Y-m-d',
        ]);

        $user = auth()->user();

        $from = Carbon::parse($req->input('from'))->format('Y-m-d H:i:s');
        $to = Carbon::parse($req->input('to'))
            ->addHour(23)
            ->addMinute(59)
            ->addSecond(59)
            ->format('Y-m-d H:i:s');

        $totalMinutes = Attendance::where('user_id', $user->id)
            ->whereBetween('check_in', [$from, $to])
            ->whereNotNull('check_out')
            ->sum('duration');

        $totalHours = number_format($totalMinutes / 60, 2);

        return $this->successResponse(data: [
            'totalHours' => $totalHours,
        ], message: "total hours ($totalHours hours)");
    }
}

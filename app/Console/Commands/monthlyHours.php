<?php

namespace App\Console\Commands;

use App\Mail\NotifyHours;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class monthlyHours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:monthly-hours';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users about monthly hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $month = Carbon::now()->subMonth()->month;

        $attendances = Attendance::select('user_id', DB::raw('MAX(id) as max_id'))
            ->with(['user'])
            ->whereMonth('check_in', $month)
            ->groupBy('user_id')
            ->selectRaw('ROUND(sum(duration) / 60, 2) as total_hours')
            ->get();

        foreach ($attendances as $attendance) {
            $user = $attendance->user;

            Mail::to($user->email)->queue(new NotifyHours($user, $attendance->total_hours));
        }
    }
}

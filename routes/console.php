<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {

    Schedule::command('app:monthly-hours')
        ->monthlyOn(1, '00:00');

})->purpose('Display an inspiring quote')->hourly();

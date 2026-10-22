<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Tandai karyawan yang tidak absen sebagai alpha setiap akhir hari.
Schedule::command('absensi:mark-alpha')->dailyAt('23:30')->withoutOverlapping();

Schedule::call(function () {
    \Illuminate\Support\Facades\Log::info(
        'TEST SCHEDULER: ' . now('Asia/Jakarta')->toDateTimeString()
    );
})->everyMinute();

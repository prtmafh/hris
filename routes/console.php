<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Tandai karyawan yang tidak absen sebagai alpha setiap hari jam 00:05
Schedule::command('absensi:mark-alpha')->dailyAt('00:05');

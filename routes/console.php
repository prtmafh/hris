
<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Tandai karyawan yang tidak absen sebagai alpha setiap akhir hari.

Schedule::call(function () {
    Log::info('Scheduler alpha mulai dijalankan.');

    Artisan::call('absensi:mark-alpha');

    Log::info('Scheduler alpha selesai.', [
        'output' => Artisan::output(),
    ]);
})
    ->name('mark-alpha-absensi')
    ->dailyAt('17:30')
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping();

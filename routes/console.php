<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


/*
|--------------------------------------------------------------------------
| Command Bawaan Laravel
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {

    $this->comment(
        Inspiring::quote()
    );

})->purpose(
    'Display an inspiring quote'
);


/*
|--------------------------------------------------------------------------
| Alpha Otomatis Apel Pagi
|--------------------------------------------------------------------------
|
| Setiap hari Senin pukul 07:45 WITA,
| sistem menjalankan command apel:generate-alpha.
|
*/

Schedule::command(
    'apel:generate-alpha'
)
    ->mondays()
    ->at(
        config(
            'attendance.end_time',
            '07:45'
        )
    )
    ->timezone(
        'Asia/Makassar'
    )
    ->name(
        'apel-pagi-alpha-otomatis'
    )
    ->withoutOverlapping();
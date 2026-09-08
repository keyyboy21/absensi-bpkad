<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Lokasi Kantor
    |--------------------------------------------------------------------------
    */

    'latitude' => env('OFFICE_LATITUDE', 0),

    'longitude' => env('OFFICE_LONGITUDE', 0),

    'radius' => env('OFFICE_RADIUS', 150),


    /*
    |--------------------------------------------------------------------------
    | Waktu Absensi Apel Pagi
    |--------------------------------------------------------------------------
    */

    'start_time' => env('ATTENDANCE_START_TIME', '07:30'),

    'end_time' => env('ATTENDANCE_END_TIME', '07:45'),


    /*
    |--------------------------------------------------------------------------
    | Mode Simulasi / Testing
    |--------------------------------------------------------------------------
    |
    | Digunakan hanya saat pengembangan untuk mensimulasikan hari Senin
    | dan waktu absensi tanpa mengubah tanggal/jam komputer.
    |
    | Pastikan ATTENDANCE_TEST_MODE=false saat aplikasi digunakan
    | secara normal.
    |
    */

    'test_mode' => env('ATTENDANCE_TEST_MODE', false),

    'test_date' => env('ATTENDANCE_TEST_DATE', null),

    'test_time' => env('ATTENDANCE_TEST_TIME', null),

];
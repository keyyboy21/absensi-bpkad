<?php

namespace App\Helpers;

use Carbon\Carbon;

class AttendanceTime
{
    public static function now(): Carbon
    {
        if (
            config('attendance.test_mode') &&
            config('attendance.test_date') &&
            config('attendance.test_time')
        ) {
            return Carbon::createFromFormat(
                'Y-m-d H:i',
                config('attendance.test_date')
                . ' '
                . config('attendance.test_time'),
                config('app.timezone')
            );
        }

        return Carbon::now();
    }

    public static function today(): Carbon
    {
        return self::now()->copy()->startOfDay();
    }
}
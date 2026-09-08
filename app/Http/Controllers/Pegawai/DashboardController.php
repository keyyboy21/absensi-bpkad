<?php

namespace App\Http\Controllers\Pegawai;

use App\Helpers\AttendanceTime;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Gunakan Tanggal Aplikasi / Simulasi
        |--------------------------------------------------------------------------
        |
        | Jika ATTENDANCE_TEST_MODE=true, tanggal mengikuti
        | ATTENDANCE_TEST_DATE.
        |
        */

        $today = AttendanceTime::today();


        /*
        |--------------------------------------------------------------------------
        | Cek Hari Apel
        |--------------------------------------------------------------------------
        |
        | Apel Pagi hanya dilaksanakan setiap hari Senin.
        |
        */

        $isSenin = $today->isMonday();


        /*
        |--------------------------------------------------------------------------
        | Absensi Apel Hari Ini
        |--------------------------------------------------------------------------
        |
        | Hanya mencari absensi hari ini apabila hari Senin.
        |
        */

        $absensiHariIni = null;

        if ($isSenin) {
            $absensiHariIni = Absensi::where(
                'user_id',
                $user->id
            )
                ->whereDate(
                    'tanggal',
                    $today
                )
                ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | Riwayat Apel Terbaru
        |--------------------------------------------------------------------------
        |
        | DAYOFWEEK MySQL:
        |
        | 1 = Minggu
        | 2 = Senin
        |
        | Karena sistem khusus Apel Pagi hari Senin,
        | data selain hari Senin tidak ditampilkan.
        |
        */

        $riwayatAbsensi = Absensi::where(
            'user_id',
            $user->id
        )
            ->whereRaw(
                'DAYOFWEEK(tanggal) = 2'
            )
            ->orderBy(
                'tanggal',
                'desc'
            )
            ->orderBy(
                'jam_masuk',
                'desc'
            )
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Tampilkan Dashboard Pegawai
        |--------------------------------------------------------------------------
        */

        return view(
            'pegawai.dashboard',
            compact(
                'absensiHariIni',
                'riwayatAbsensi',
                'isSenin'
            )
        );
    }
}
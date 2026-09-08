<?php

use App\Helpers\AttendanceTime;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\Admin\AbsensiController as AdminAbsensiController;

use App\Http\Controllers\PegawaiImportController;

use App\Http\Controllers\Pegawai\AbsensiController;
use App\Http\Controllers\Pegawai\QrCodeController as PegawaiQrCodeController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;


/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Dashboard Default Breeze
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware([
        'auth',
        'verified',
    ])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');


    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');


    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'admin',
])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard Admin
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Data Pegawai
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/pegawai/import',
            [PegawaiImportController::class, 'import']
        )->name('pegawai.import');


        Route::resource(
            'pegawai',
            PegawaiController::class
        )->except([
            'show',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Riwayat Absensi Apel Pagi
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/absensi',
            [AdminAbsensiController::class, 'index']
        )->name('absensi.index');


        /*
        |--------------------------------------------------------------------------
        | Laporan Absensi Apel Pagi
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/laporan',
            [AdminAbsensiController::class, 'laporan']
        )->name('laporan.index');


        Route::get(
            '/laporan/pdf',
            [AdminAbsensiController::class, 'exportPdf']
        )->name('laporan.pdf');


        /*
        |--------------------------------------------------------------------------
        | Detail Absensi Apel Pagi
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/absensi/{absensi}',
            [AdminAbsensiController::class, 'show']
        )->name('absensi.show');


        /*
        |--------------------------------------------------------------------------
        | QR Code Apel Pagi
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/qrcode',
            [QrCodeController::class, 'index']
        )->name('qrcode.index');


        Route::post(
            '/qrcode/generate-permanent',
            [QrCodeController::class, 'generatePermanent']
        )->name('qrcode.generatePermanent');
    });


/*
|--------------------------------------------------------------------------
| Pegawai
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'pegawai',
])
    ->prefix('pegawai')
    ->name('pegawai.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard Pegawai
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [PegawaiDashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Absensi Apel Pagi
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/absensi',
            [AbsensiController::class, 'index']
        )->name('absensi.index');


        /*
        |--------------------------------------------------------------------------
        | Riwayat Absensi Apel Pagi
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/riwayat',
            [AbsensiController::class, 'riwayat']
        )->name('riwayat');


        /*
        |--------------------------------------------------------------------------
        | Kirim Absensi Apel Pagi
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/absensi/masuk',
            [AbsensiController::class, 'masuk']
        )->name('absensi.masuk');


        /*
        |--------------------------------------------------------------------------
        | Scan QR Code Apel Pagi
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/qrcode/{token}',
            [PegawaiQrCodeController::class, 'verify']
        )->name('qrcode.verify');


        /*
        |--------------------------------------------------------------------------
        | Verifikasi Setelah Scan QR
        |--------------------------------------------------------------------------
        */

        Route::get('/verifikasi', function () {

            /*
            |--------------------------------------------------------------------------
            | Waktu Aplikasi / Simulasi
            |--------------------------------------------------------------------------
            */

            $now = AttendanceTime::now();
            $today = AttendanceTime::today();


            /*
            |--------------------------------------------------------------------------
            | Harus Hari Senin
            |--------------------------------------------------------------------------
            */

            if (!$today->isMonday()) {

                session()->forget([
                    'qr_valid',
                    'qr_tipe',
                    'qr_token',
                    'qr_valid_until',
                ]);

                return redirect()
                    ->route('pegawai.dashboard')
                    ->with(
                        'error',
                        'Absensi Apel Pagi hanya dapat dilakukan pada hari Senin.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Batas Waktu Absensi
            |--------------------------------------------------------------------------
            |
            | Mulai pukul 07:45 WITA halaman verifikasi tidak dapat dibuka.
            |
            */

            $jamTutup = config(
                'attendance.end_time',
                '07:45'
            );


            $batasAbsensi = $today
                ->copy()
                ->setTimeFromTimeString(
                    $jamTutup
                );


            if ($now->greaterThanOrEqualTo($batasAbsensi)) {

                session()->forget([
                    'qr_valid',
                    'qr_tipe',
                    'qr_token',
                    'qr_valid_until',
                ]);

                return redirect()
                    ->route('pegawai.dashboard')
                    ->with(
                        'error',
                        'Absensi Apel Pagi telah ditutup pada pukul '
                        . $batasAbsensi->format('H:i')
                        . ' WITA.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | QR Belum Diverifikasi
            |--------------------------------------------------------------------------
            */

            if (
                !session('qr_valid') ||
                !session('qr_valid_until')
            ) {

                session()->forget([
                    'qr_valid',
                    'qr_tipe',
                    'qr_token',
                    'qr_valid_until',
                ]);

                return redirect()
                    ->route('pegawai.dashboard')
                    ->with(
                        'error',
                        'Silakan scan QR Code Apel Pagi terlebih dahulu.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Sesi QR Sudah Kedaluwarsa
            |--------------------------------------------------------------------------
            |
            | Masa berlaku QR tetap menggunakan waktu nyata.
            |
            | Jadi setelah QR dipindai, pegawai tetap hanya mempunyai
            | waktu 3 menit untuk mengirim absensi.
            |
            */

            if (
                now()->timestamp >
                session('qr_valid_until')
            ) {

                session()->forget([
                    'qr_valid',
                    'qr_tipe',
                    'qr_token',
                    'qr_valid_until',
                ]);

                return redirect()
                    ->route('pegawai.dashboard')
                    ->with(
                        'error',
                        'Sesi QR Code Apel Pagi telah berakhir. Silakan scan QR kembali.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | QR Masih Valid
            |--------------------------------------------------------------------------
            */

            return view(
                'pegawai.verifikasi'
            );

        })->name('verifikasi');
    });


/*
|--------------------------------------------------------------------------
| Authentication Breeze
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
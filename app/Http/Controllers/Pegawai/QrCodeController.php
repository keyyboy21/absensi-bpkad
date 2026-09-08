<?php

namespace App\Http\Controllers\Pegawai;

use App\Helpers\AttendanceTime;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\QrCode;
use Illuminate\Support\Facades\Auth;

class QrCodeController extends Controller
{
    /**
     * Verifikasi QR Code Apel Pagi.
     */
    public function verify(string $token)
    {
        /*
        |--------------------------------------------------------------------------
        | Gunakan Waktu Aplikasi / Simulasi
        |--------------------------------------------------------------------------
        |
        | Jika ATTENDANCE_TEST_MODE=true:
        | waktu mengikuti ATTENDANCE_TEST_DATE dan ATTENDANCE_TEST_TIME.
        |
        */

        $now = AttendanceTime::now();
        $today = AttendanceTime::today();


        /*
        |--------------------------------------------------------------------------
        | Absensi Apel Hanya Hari Senin
        |--------------------------------------------------------------------------
        */

        if (!$today->isMonday()) {
            $this->hapusSessionQr();

            return redirect()
                ->route('pegawai.dashboard')
                ->with(
                    'error',
                    'Absensi Apel Pagi hanya dapat dilakukan pada hari Senin.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Batas Waktu Scan QR
        |--------------------------------------------------------------------------
        |
        | Mulai pukul 07:45 WITA QR tidak dapat digunakan lagi.
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
            $this->hapusSessionQr();

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
        | Cari QR Code
        |--------------------------------------------------------------------------
        */

        $qrCode = QrCode::where(
            'token',
            $token
        )
            ->where(
                'aktif',
                true
            )
            ->first();


        if (!$qrCode) {
            $this->hapusSessionQr();

            return redirect()
                ->route('pegawai.dashboard')
                ->with(
                    'error',
                    'QR Code Apel Pagi tidak valid.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Validasi QR Dynamic
        |--------------------------------------------------------------------------
        |
        | QR permanen tidak menggunakan expired_at.
        |
        | Pengecekan QR dynamic tetap menggunakan waktu nyata karena
        | expired_at adalah masa berlaku QR yang sebenarnya.
        |
        */

        if (
            $qrCode->mode === 'dynamic' &&
            (
                !$qrCode->expired_at ||
                $qrCode->expired_at->isPast()
            )
        ) {
            $this->hapusSessionQr();

            return redirect()
                ->route('pegawai.dashboard')
                ->with(
                    'error',
                    'QR Code Apel Pagi sudah kedaluwarsa.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Pastikan Pegawai Belum Mengirim Absensi
        |--------------------------------------------------------------------------
        |
        | Tanggal yang diperiksa mengikuti tanggal aplikasi/simulasi.
        |
        */

        $absensiSudahAda = Absensi::where(
            'user_id',
            Auth::id()
        )
            ->whereDate(
                'tanggal',
                $today
            )
            ->exists();


        if ($absensiSudahAda) {
            $this->hapusSessionQr();

            return redirect()
                ->route('pegawai.absensi.index')
                ->with(
                    'error',
                    'Anda sudah mengirim absensi Apel Pagi hari ini.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Buat Session Hasil Scan QR
        |--------------------------------------------------------------------------
        |
        | PENTING:
        |
        | Session QR tetap menggunakan now() asli.
        |
        | Jadi walaupun waktu Apel sedang disimulasikan, pegawai tetap
        | hanya mempunyai waktu 3 menit secara nyata setelah scan QR.
        |
        */

        session([
            'qr_valid' => true,

            'qr_token' =>
                $qrCode->token,

            'qr_valid_until' =>
                now()
                    ->addMinutes(3)
                    ->timestamp,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Buka Halaman Verifikasi
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('pegawai.verifikasi');
    }


    /**
     * Menghapus session QR lama.
     */
    private function hapusSessionQr(): void
    {
        session()->forget([
            'qr_valid',
            'qr_tipe',
            'qr_token',
            'qr_valid_until',
        ]);
    }
}
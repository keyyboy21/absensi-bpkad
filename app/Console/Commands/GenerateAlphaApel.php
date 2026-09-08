<?php

namespace App\Console\Commands;

use App\Helpers\AttendanceTime;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Console\Command;

class GenerateAlphaApel extends Command
{
    protected $signature = 'apel:generate-alpha';

    protected $description = 'Membuat status Alpha otomatis untuk pegawai yang belum mengisi absensi Apel Pagi';

    public function handle(): int
    {
        $today = AttendanceTime::today();

        /*
        |--------------------------------------------------------------------------
        | Pastikan Hari Senin
        |--------------------------------------------------------------------------
        */

        if (!$today->isMonday()) {
            $this->error(
                'Proses Alpha hanya dapat dilakukan untuk hari Senin.'
            );

            return self::FAILURE;
        }


        /*
        |--------------------------------------------------------------------------
        | Cari Pegawai Aktif Yang Belum Memiliki Absensi
        |--------------------------------------------------------------------------
        */

        $pegawaiBelumAbsen = User::where('role', 'pegawai')
            ->where('status', 'aktif')
            ->whereDoesntHave('absensis', function ($query) use ($today) {
                $query->whereDate(
                    'tanggal',
                    $today->toDateString()
                );
            })
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Tidak Ada Pegawai Yang Perlu Dijadikan Alpha
        |--------------------------------------------------------------------------
        */

        if ($pegawaiBelumAbsen->isEmpty()) {
            $this->info(
                'Tidak ada pegawai yang perlu ditetapkan sebagai Alpha.'
            );

            return self::SUCCESS;
        }


        /*
        |--------------------------------------------------------------------------
        | Buat Data Alpha
        |--------------------------------------------------------------------------
        */

        $jumlahAlpha = 0;

        foreach ($pegawaiBelumAbsen as $pegawai) {

            $absensi = Absensi::firstOrCreate(
                [
                    'user_id' => $pegawai->id,
                    'tanggal' => $today->toDateString(),
                ],
                [
                    'jam_masuk' => null,
                    'foto_masuk' => null,
                    'latitude_masuk' => null,
                    'longitude_masuk' => null,
                    'status' => 'alpha',
                    'alasan_tidak_hadir' => null,
                    'keterangan' => null,
                ]
            );

            if ($absensi->wasRecentlyCreated) {
                $jumlahAlpha++;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Informasi Hasil
        |--------------------------------------------------------------------------
        */

        $this->info(
            $jumlahAlpha .
            ' pegawai berhasil ditetapkan sebagai Alpha.'
        );

        return self::SUCCESS;
    }
}
<?php

namespace App\Http\Controllers\Pegawai;

use App\Helpers\AttendanceTime;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AbsensiController extends Controller
{
    /**
     * Menampilkan status absensi Apel Pagi pegawai.
     */
    public function index()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Gunakan Waktu Aplikasi / Simulasi
        |--------------------------------------------------------------------------
        */

        $today = AttendanceTime::today();

        $absensiHariIni = null;


        /*
        |--------------------------------------------------------------------------
        | Absensi Hari Ini Hanya Berlaku Hari Senin
        |--------------------------------------------------------------------------
        */

        if ($today->isMonday()) {
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


        return view(
            'pegawai.absensi.index',
            compact('absensiHariIni')
        );
    }


    /**
     * Menampilkan riwayat absensi Apel Pagi pegawai.
     */
    public function riwayat()
    {
        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Riwayat Khusus Hari Senin
        |--------------------------------------------------------------------------
        |
        | DAYOFWEEK MySQL:
        |
        | 1 = Minggu
        | 2 = Senin
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
            ->paginate(10);


        return view(
            'pegawai.absensi.riwayat',
            compact('riwayatAbsensi')
        );
    }


    /**
     * Proses pengiriman absensi Apel Pagi.
     */
    public function masuk(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Gunakan Waktu Aplikasi / Simulasi
        |--------------------------------------------------------------------------
        */

        $now = AttendanceTime::now();

        $today = AttendanceTime::today();


        /*
        |--------------------------------------------------------------------------
        | Absensi Hanya Hari Senin
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
        | Batas Waktu Absensi
        |--------------------------------------------------------------------------
        |
        | ATTENDANCE_END_TIME = 07:45
        |
        | Mulai pukul 07:45 WITA absensi sudah ditutup.
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
        | Validasi Session QR
        |--------------------------------------------------------------------------
        |
        | Session QR tetap menggunakan waktu nyata.
        | QR hanya berlaku selama 3 menit sejak discan.
        |
        */

        if (
            !session('qr_valid') ||
            !session('qr_valid_until') ||
            now()->timestamp > session('qr_valid_until')
        ) {
            $this->hapusSessionQr();

            return redirect()
                ->route('pegawai.dashboard')
                ->with(
                    'error',
                    'QR Code belum diverifikasi atau sesi QR telah berakhir. Silakan scan QR kembali.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Data Pegawai
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Cek Apakah Sudah Mengirim Absensi
        |--------------------------------------------------------------------------
        */

        $absensiSudahAda = Absensi::where(
            'user_id',
            $user->id
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
        | Validasi Pilihan Kehadiran
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'kehadiran' => [
                'required',

                Rule::in([
                    'hadir',
                    'tidak_hadir',
                ]),
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Jika Pegawai Hadir
        |--------------------------------------------------------------------------
        */

        if ($request->kehadiran === 'hadir') {
            return $this->prosesHadir(
                $request,
                $user,
                $today,
                $now
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Jika Pegawai Tidak Hadir
        |--------------------------------------------------------------------------
        */

        return $this->prosesTidakHadir(
            $request,
            $user,
            $today,
            $now
        );
    }


    /**
     * Proses pegawai yang hadir Apel Pagi.
     */
    private function prosesHadir(
        Request $request,
        $user,
        Carbon $today,
        Carbon $now
    ) {
        /*
        |--------------------------------------------------------------------------
        | Validasi GPS + Selfie
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],

            'foto' => [
                'required',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Hitung Jarak Pegawai Dengan Kantor
        |--------------------------------------------------------------------------
        */

        $jarak = $this->hitungJarak(
            (float) $request->latitude,
            (float) $request->longitude
        );


        $radiusKantor = (float) config(
            'attendance.radius',
            150
        );


        if ($jarak > $radiusKantor) {
            return redirect()
                ->route('pegawai.verifikasi')
                ->withInput()
                ->with(
                    'error',
                    'Anda berada di luar radius kantor. Jarak Anda sekitar '
                    . round($jarak)
                    . ' meter.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Waktu Mulai Apel
        |--------------------------------------------------------------------------
        |
        | ATTENDANCE_START_TIME = 07:30
        |
        | Sampai pukul 07:30:00 = Hadir
        | Setelah pukul 07:30:00 = Terlambat
        |
        */

        $jamApel = config(
            'attendance.start_time',
            '07:30'
        );


        $batasTerlambat = $today
            ->copy()
            ->setTimeFromTimeString(
                $jamApel
            );


        /*
        |--------------------------------------------------------------------------
        | Tentukan Status
        |--------------------------------------------------------------------------
        */

        $status = $now->greaterThan(
            $batasTerlambat
        )
            ? 'terlambat'
            : 'hadir';


        /*
        |--------------------------------------------------------------------------
        | Simpan Selfie
        |--------------------------------------------------------------------------
        */

        $fotoApel = $this->simpanFotoBase64(
            $request->foto,
            'apel',
            $user->id
        );


        /*
        |--------------------------------------------------------------------------
        | Simpan Absensi Apel
        |--------------------------------------------------------------------------
        */

        Absensi::create([

            'user_id' =>
                $user->id,

            'tanggal' =>
                $today->toDateString(),

            'jam_masuk' =>
                $now->format('H:i:s'),

            'foto_masuk' =>
                $fotoApel,

            'latitude_masuk' =>
                $request->latitude,

            'longitude_masuk' =>
                $request->longitude,

            'status' =>
                $status,

            'alasan_tidak_hadir' =>
                null,

            'keterangan' =>
                null,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Hapus Session QR
        |--------------------------------------------------------------------------
        */

        $this->hapusSessionQr();


        return redirect()
            ->route('pegawai.absensi.index')
            ->with(
                'success',
                $status === 'terlambat'
                    ? 'Absensi Apel Pagi berhasil dikirim. Anda tercatat terlambat.'
                    : 'Absensi Apel Pagi berhasil dikirim. Anda tercatat hadir.'
            );
    }


    /**
     * Proses pegawai yang tidak hadir Apel Pagi.
     */
    private function prosesTidakHadir(
        Request $request,
        $user,
        Carbon $today,
        Carbon $now
    ) {
        /*
        |--------------------------------------------------------------------------
        | Validasi Alasan Tidak Hadir
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'alasan_tidak_hadir' => [
                'required',

                Rule::in([
                    'izin',
                    'sakit',
                    'dinas_luar',
                    'lainnya',
                ]),
            ],

            'keterangan' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Tentukan Status
        |--------------------------------------------------------------------------
        */

        $status = $request->alasan_tidak_hadir;


        /*
        |--------------------------------------------------------------------------
        | Simpan Absensi Tidak Hadir
        |--------------------------------------------------------------------------
        */

        Absensi::create([

            'user_id' =>
                $user->id,

            'tanggal' =>
                $today->toDateString(),

            'jam_masuk' =>
                $now->format('H:i:s'),

            'foto_masuk' =>
                null,

            'latitude_masuk' =>
                null,

            'longitude_masuk' =>
                null,

            'status' =>
                $status,

            'alasan_tidak_hadir' =>
                $request->alasan_tidak_hadir,

            'keterangan' =>
                trim($request->keterangan),

        ]);


        /*
        |--------------------------------------------------------------------------
        | Hapus Session QR
        |--------------------------------------------------------------------------
        */

        $this->hapusSessionQr();


        return redirect()
            ->route('pegawai.absensi.index')
            ->with(
                'success',
                'Keterangan tidak hadir Apel Pagi berhasil dikirim.'
            );
    }


    /**
     * Menghitung jarak pegawai dengan kantor dalam meter.
     */
    private function hitungJarak(
        float $latitudePegawai,
        float $longitudePegawai
    ): float {

        $latitudeKantor = (float) config(
            'attendance.latitude'
        );


        $longitudeKantor = (float) config(
            'attendance.longitude'
        );


        /*
        |--------------------------------------------------------------------------
        | Radius Bumi Dalam Meter
        |--------------------------------------------------------------------------
        */

        $earthRadius = 6371000;


        /*
        |--------------------------------------------------------------------------
        | Konversi Koordinat Ke Radian
        |--------------------------------------------------------------------------
        */

        $latFrom = deg2rad(
            $latitudeKantor
        );


        $lonFrom = deg2rad(
            $longitudeKantor
        );


        $latTo = deg2rad(
            $latitudePegawai
        );


        $lonTo = deg2rad(
            $longitudePegawai
        );


        /*
        |--------------------------------------------------------------------------
        | Selisih Koordinat
        |--------------------------------------------------------------------------
        */

        $latDelta =
            $latTo - $latFrom;


        $lonDelta =
            $lonTo - $lonFrom;


        /*
        |--------------------------------------------------------------------------
        | Rumus Haversine
        |--------------------------------------------------------------------------
        */

        $angle = 2 * asin(
            sqrt(
                pow(
                    sin($latDelta / 2),
                    2
                )
                +
                cos($latFrom)
                *
                cos($latTo)
                *
                pow(
                    sin($lonDelta / 2),
                    2
                )
            )
        );


        return $angle * $earthRadius;
    }


    /**
     * Menyimpan selfie Base64 ke storage.
     */
    private function simpanFotoBase64(
        string $fotoBase64,
        string $tipe,
        int $userId
    ): string {

        /*
        |--------------------------------------------------------------------------
        | Validasi Format Base64
        |--------------------------------------------------------------------------
        */

        if (
            !preg_match(
                '/^data:image\/(jpeg|jpg|png);base64,/',
                $fotoBase64,
                $matches
            )
        ) {
            abort(
                422,
                'Format foto selfie tidak valid.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pisahkan Data Base64
        |--------------------------------------------------------------------------
        */

        $fotoBase64 = substr(
            $fotoBase64,
            strpos(
                $fotoBase64,
                ','
            ) + 1
        );


        $fotoBase64 = str_replace(
            ' ',
            '+',
            $fotoBase64
        );


        /*
        |--------------------------------------------------------------------------
        | Decode Base64
        |--------------------------------------------------------------------------
        */

        $fotoDecoded = base64_decode(
            $fotoBase64,
            true
        );


        if ($fotoDecoded === false) {
            abort(
                422,
                'Foto selfie tidak dapat diproses.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Maksimal Ukuran Selfie 5 MB
        |--------------------------------------------------------------------------
        */

        if (
            strlen(
                $fotoDecoded
            ) > 5 * 1024 * 1024
        ) {
            abort(
                422,
                'Ukuran foto selfie terlalu besar.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Tentukan Ekstensi
        |--------------------------------------------------------------------------
        */

        $extension =
            $matches[1] === 'png'
                ? 'png'
                : 'jpg';


        /*
        |--------------------------------------------------------------------------
        | Buat Nama File
        |--------------------------------------------------------------------------
        |
        | Nama file mengikuti waktu simulasi jika test mode aktif.
        |
        */

        $namaFile =
            $userId
            . '_'
            . AttendanceTime::now()->format(
                'Ymd_His'
            )
            . '_'
            . $tipe
            . '.'
            . $extension;


        /*
        |--------------------------------------------------------------------------
        | Folder Penyimpanan
        |--------------------------------------------------------------------------
        */

        $path =
            'absensi/'
            . $tipe
            . '/'
            . $namaFile;


        /*
        |--------------------------------------------------------------------------
        | Simpan File
        |--------------------------------------------------------------------------
        */

        Storage::disk(
            'public'
        )->put(
            $path,
            $fotoDecoded
        );


        return $path;
    }


    /**
     * Menghapus session hasil scan QR.
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
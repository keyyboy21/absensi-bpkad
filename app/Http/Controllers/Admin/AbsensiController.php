<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * Membuat query dasar khusus data Apel Pagi.
     */
    private function baseQuery()
    {
        return Absensi::with('user')
            ->whereRaw('DAYOFWEEK(tanggal) = 2');
    }


    /**
     * Menerapkan filter umum.
     */
    private function applyFilter(
        $query,
        Request $request,
        bool $includeStatus = true
    ) {
        /*
        |--------------------------------------------------------------------------
        | Filter Pegawai
        |--------------------------------------------------------------------------
        */

        if ($request->filled('pegawai')) {

            $keyword = $request->pegawai;

            $query->whereHas(
                'user',
                function ($q) use ($keyword) {

                    $q->where(
                        function ($subQuery) use ($keyword) {

                            $subQuery
                                ->where(
                                    'name',
                                    'like',
                                    '%' . $keyword . '%'
                                )
                                ->orWhere(
                                    'nip',
                                    'like',
                                    '%' . $keyword . '%'
                                );
                        }
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Filter Tanggal Awal
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tanggal_awal')) {

            $query->whereDate(
                'tanggal',
                '>=',
                $request->tanggal_awal
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Filter Tanggal Akhir
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tanggal_akhir')) {

            $query->whereDate(
                'tanggal',
                '<=',
                $request->tanggal_akhir
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Filter Status
        |--------------------------------------------------------------------------
        */

        if (
            $includeStatus &&
            $request->filled('status')
        ) {

            $query->where(
                'status',
                $request->status
            );
        }


        return $query;
    }


    /**
     * Menampilkan seluruh riwayat Apel Pagi.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Query Riwayat Apel
        |--------------------------------------------------------------------------
        */

        $query = $this->baseQuery();

        $this->applyFilter(
            $query,
            $request
        );


        /*
        |--------------------------------------------------------------------------
        | Data Riwayat
        |--------------------------------------------------------------------------
        */

        $absensis = $query
            ->orderBy(
                'tanggal',
                'desc'
            )
            ->orderBy(
                'jam_masuk',
                'desc'
            )
            ->paginate(10)
            ->withQueryString();


        return view(
            'admin.absensi.index',
            compact(
                'absensis'
            )
        );
    }


    /**
     * Menampilkan laporan Apel Pagi.
     */
    public function laporan(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Query Statistik
        |--------------------------------------------------------------------------
        |
        | Filter pegawai dan tanggal diterapkan.
        | Filter status tidak diterapkan agar seluruh statistik tetap terlihat.
        |
        */

        $statistikQuery = $this->baseQuery();

        $this->applyFilter(
            $statistikQuery,
            $request,
            false
        );


        /*
        |--------------------------------------------------------------------------
        | Statistik
        |--------------------------------------------------------------------------
        */

        $totalData = (clone $statistikQuery)
            ->count();


        $totalHadir = (clone $statistikQuery)
            ->where(
                'status',
                'hadir'
            )
            ->count();


        $totalTerlambat = (clone $statistikQuery)
            ->where(
                'status',
                'terlambat'
            )
            ->count();


        $totalIzin = (clone $statistikQuery)
            ->where(
                'status',
                'izin'
            )
            ->count();


        $totalSakit = (clone $statistikQuery)
            ->where(
                'status',
                'sakit'
            )
            ->count();


        $totalDinasLuar = (clone $statistikQuery)
            ->where(
                'status',
                'dinas_luar'
            )
            ->count();


        $totalLainnya = (clone $statistikQuery)
            ->where(
                'status',
                'lainnya'
            )
            ->count();


        $totalAlpha = (clone $statistikQuery)
            ->where(
                'status',
                'alpha'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Query Data Laporan
        |--------------------------------------------------------------------------
        |
        | Di sini filter status tetap diterapkan.
        |
        */

        $query = $this->baseQuery();

        $this->applyFilter(
            $query,
            $request,
            true
        );


        /*
        |--------------------------------------------------------------------------
        | Data Laporan
        |--------------------------------------------------------------------------
        */

        $laporan = $query
            ->orderBy(
                'tanggal',
                'desc'
            )
            ->orderBy(
                'jam_masuk',
                'desc'
            )
            ->paginate(15)
            ->withQueryString();


        return view(
            'admin.laporan.index',
            compact(
                'laporan',
                'totalData',
                'totalHadir',
                'totalTerlambat',
                'totalIzin',
                'totalSakit',
                'totalDinasLuar',
                'totalLainnya',
                'totalAlpha'
            )
        );
    }


    /**
     * Export laporan Apel Pagi menjadi PDF.
     */
    public function exportPdf(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Query Statistik
        |--------------------------------------------------------------------------
        */

        $statistikQuery = $this->baseQuery();

        $this->applyFilter(
            $statistikQuery,
            $request,
            false
        );


        /*
        |--------------------------------------------------------------------------
        | Statistik PDF
        |--------------------------------------------------------------------------
        */

        $totalData = (clone $statistikQuery)
            ->count();


        $totalHadir = (clone $statistikQuery)
            ->where(
                'status',
                'hadir'
            )
            ->count();


        $totalTerlambat = (clone $statistikQuery)
            ->where(
                'status',
                'terlambat'
            )
            ->count();


        $totalIzin = (clone $statistikQuery)
            ->where(
                'status',
                'izin'
            )
            ->count();


        $totalSakit = (clone $statistikQuery)
            ->where(
                'status',
                'sakit'
            )
            ->count();


        $totalDinasLuar = (clone $statistikQuery)
            ->where(
                'status',
                'dinas_luar'
            )
            ->count();


        $totalLainnya = (clone $statistikQuery)
            ->where(
                'status',
                'lainnya'
            )
            ->count();


        $totalAlpha = (clone $statistikQuery)
            ->where(
                'status',
                'alpha'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Query Data PDF
        |--------------------------------------------------------------------------
        */

        $query = $this->baseQuery();

        $this->applyFilter(
            $query,
            $request,
            true
        );


        /*
        |--------------------------------------------------------------------------
        | Ambil Seluruh Data
        |--------------------------------------------------------------------------
        */

        $laporan = $query
            ->orderBy(
                'tanggal',
                'asc'
            )
            ->orderBy(
                'jam_masuk',
                'asc'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'admin.laporan.pdf',
            compact(
                'laporan',
                'totalData',
                'totalHadir',
                'totalTerlambat',
                'totalIzin',
                'totalSakit',
                'totalDinasLuar',
                'totalLainnya',
                'totalAlpha'
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Ukuran Kertas
        |--------------------------------------------------------------------------
        */

        $pdf->setPaper(
            'a4',
            'landscape'
        );


        /*
        |--------------------------------------------------------------------------
        | Nama File
        |--------------------------------------------------------------------------
        */

        $namaFile =
            'laporan-apel-pagi-'
            . now()->format(
                'Y-m-d-His'
            )
            . '.pdf';


        /*
        |--------------------------------------------------------------------------
        | Download PDF
        |--------------------------------------------------------------------------
        */

        return $pdf->download(
            $namaFile
        );
    }


    /**
     * Menampilkan detail satu absensi Apel Pagi.
     */
    public function show(Absensi $absensi)
    {
        /*
        |--------------------------------------------------------------------------
        | Pastikan Data Merupakan Hari Senin
        |--------------------------------------------------------------------------
        |
        | Data absensi lama hari selain Senin tidak boleh dianggap
        | sebagai data Apel Pagi.
        |
        */

        if (
            !$absensi->tanggal ||
            !\Carbon\Carbon::parse(
                $absensi->tanggal
            )->isMonday()
        ) {

            abort(
                404,
                'Data Apel Pagi tidak ditemukan.'
            );
        }


        $absensi->load(
            'user'
        );


        return view(
            'admin.absensi.show',
            compact(
                'absensi'
            )
        );
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Pengajuan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    /**
     * Menampilkan daftar pengajuan izin / sakit.
     */
    public function index(Request $request)
    {
        $query = Pengajuan::with('user')
            ->orderByDesc('created_at');

        if ($request->filled('pegawai')) {
            $keyword = $request->pegawai;

            $query->whereHas('user', function ($q) use ($keyword) {
                $q->where(function ($subQuery) use ($keyword) {
                    $subQuery
                        ->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('nip', 'like', '%' . $keyword . '%');
                });
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->paginate(10)->withQueryString();

        return view(
            'admin.pengajuan.index',
            compact('pengajuans')
        );
    }


    /**
     * Menampilkan detail pengajuan.
     */
    public function show(Pengajuan $pengajuan)
    {
        $pengajuan->load('user');

        return view(
            'admin.pengajuan.show',
            compact('pengajuan')
        );
    }


    /**
     * Menyetujui pengajuan.
     */
    public function approve(Request $request, Pengajuan $pengajuan)
    {
        if ($pengajuan->status !== 'menunggu') {
            return back()->with(
                'error',
                'Pengajuan ini sudah diproses sebelumnya.'
            );
        }

        $request->validate([
            'catatan_admin' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        try {

            DB::transaction(function () use ($request, $pengajuan) {

                $tanggalMulai = Carbon::parse(
                    $pengajuan->tanggal_mulai
                )->startOfDay();

                $tanggalSelesai = Carbon::parse(
                    $pengajuan->tanggal_selesai
                )->startOfDay();

                $tanggal = $tanggalMulai->copy();

                while ($tanggal->lte($tanggalSelesai)) {

                    $sudahAdaAbsensi = Absensi::where(
                        'user_id',
                        $pengajuan->user_id
                    )
                        ->whereDate(
                            'tanggal',
                            $tanggal->toDateString()
                        )
                        ->exists();

                    if (!$sudahAdaAbsensi) {

                        Absensi::create([
                            'user_id' => $pengajuan->user_id,
                            'tanggal' => $tanggal->toDateString(),
                            'jam_masuk' => null,
                            'jam_pulang' => null,
                            'foto_masuk' => null,
                            'foto_pulang' => null,
                            'latitude_masuk' => null,
                            'longitude_masuk' => null,
                            'latitude_pulang' => null,
                            'longitude_pulang' => null,
                            'status' => $pengajuan->jenis,
                        ]);

                    }

                    $tanggal->addDay();
                }

                $pengajuan->update([
                    'status' => 'disetujui',
                    'catatan_admin' => $request->catatan_admin,
                    'diproses_pada' => now(),
                ]);

            });

        } catch (\Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'Pengajuan gagal disetujui. Silakan coba kembali.'
            );
        }

        return redirect()
            ->route('admin.pengajuan.index')
            ->with(
                'success',
                'Pengajuan berhasil disetujui.'
            );
    }


    /**
     * Menolak pengajuan.
     */
    public function reject(Request $request, Pengajuan $pengajuan)
    {
        if ($pengajuan->status !== 'menunggu') {
            return back()->with(
                'error',
                'Pengajuan ini sudah diproses sebelumnya.'
            );
        }

        $validated = $request->validate([
            'catatan_admin' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        $pengajuan->update([
            'status' => 'ditolak',
            'catatan_admin' => $validated['catatan_admin'],
            'diproses_pada' => now(),
        ]);

        return redirect()
            ->route('admin.pengajuan.index')
            ->with(
                'success',
                'Pengajuan berhasil ditolak.'
            );
    }
}
<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PengajuanController extends Controller
{
    /**
     * Menampilkan form dan riwayat pengajuan pegawai.
     */
    public function index()
    {
        $pengajuans = Pengajuan::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view(
            'pegawai.pengajuan.index',
            compact('pengajuans')
        );
    }


    /**
     * Menyimpan pengajuan izin / sakit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis' => [
                'required',
                Rule::in([
                    'izin',
                    'sakit',
                ]),
            ],

            'tanggal_mulai' => [
                'required',
                'date',
            ],

            'tanggal_selesai' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],

            'keterangan' => [
                'required',
                'string',
                'max:1000',
            ],

            'bukti' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Cek Pengajuan yang Masih Bertabrakan
        |--------------------------------------------------------------------------
        |
        | Mencegah pegawai membuat pengajuan lain pada periode yang sama
        | selama pengajuan sebelumnya belum ditolak.
        |
        */

        $adaPengajuanBentrok = Pengajuan::where(
            'user_id',
            Auth::id()
        )
            ->whereIn(
                'status',
                [
                    'menunggu',
                    'disetujui',
                ]
            )
            ->where(function ($query) use ($validated) {

                $query
                    ->whereBetween(
                        'tanggal_mulai',
                        [
                            $validated['tanggal_mulai'],
                            $validated['tanggal_selesai'],
                        ]
                    )
                    ->orWhereBetween(
                        'tanggal_selesai',
                        [
                            $validated['tanggal_mulai'],
                            $validated['tanggal_selesai'],
                        ]
                    )
                    ->orWhere(function ($q) use ($validated) {

                        $q->where(
                            'tanggal_mulai',
                            '<=',
                            $validated['tanggal_mulai']
                        )
                            ->where(
                                'tanggal_selesai',
                                '>=',
                                $validated['tanggal_selesai']
                            );
                    });

            })
            ->exists();


        if ($adaPengajuanBentrok) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Sudah ada pengajuan pada periode tanggal tersebut.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Upload Bukti
        |--------------------------------------------------------------------------
        */

        $buktiPath = null;

        if ($request->hasFile('bukti')) {

            $buktiPath = $request
                ->file('bukti')
                ->store(
                    'pengajuan',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Simpan Pengajuan
        |--------------------------------------------------------------------------
        */

        Pengajuan::create([
            'user_id' => Auth::id(),

            'jenis' => $validated['jenis'],

            'tanggal_mulai' =>
                $validated['tanggal_mulai'],

            'tanggal_selesai' =>
                $validated['tanggal_selesai'],

            'keterangan' =>
                $validated['keterangan'],

            'bukti' =>
                $buktiPath,

            'status' =>
                'menunggu',
        ]);


        return redirect()
            ->route('pegawai.pengajuan.index')
            ->with(
                'success',
                'Pengajuan berhasil dikirim dan menunggu persetujuan admin.'
            );
    }


    /**
     * Menghapus pengajuan yang masih berstatus menunggu.
     */
    public function destroy(Pengajuan $pengajuan)
    {
        /*
        |--------------------------------------------------------------------------
        | Pastikan Pengajuan Milik Pegawai Login
        |--------------------------------------------------------------------------
        */

        if ($pengajuan->user_id !== Auth::id()) {

            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Hanya Pengajuan Menunggu yang Bisa Dihapus
        |--------------------------------------------------------------------------
        */

        if ($pengajuan->status !== 'menunggu') {

            return back()->with(
                'error',
                'Pengajuan yang sudah diproses tidak dapat dihapus.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Hapus File Bukti
        |--------------------------------------------------------------------------
        */

        if (
            $pengajuan->bukti
            && Storage::disk('public')->exists(
                $pengajuan->bukti
            )
        ) {

            Storage::disk('public')->delete(
                $pengajuan->bukti
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Hapus Pengajuan
        |--------------------------------------------------------------------------
        */

        $pengajuan->delete();


        return back()->with(
            'success',
            'Pengajuan berhasil dihapus.'
        );
    }
}
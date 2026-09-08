<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use Illuminate\Support\Str;

class QrCodeController extends Controller
{
    /**
     * Menampilkan QR Code permanen Apel Pagi.
     */
    public function index()
    {
        $qrPermanen = QrCode::where(
            'mode',
            'static'
        )
            ->where(
                'aktif',
                true
            )
            ->first();

        return view(
            'admin.qrcode.index',
            compact('qrPermanen')
        );
    }


    /**
     * Membuat QR Code permanen Apel Pagi.
     */
    public function generatePermanent()
    {
        $qrPermanen = QrCode::where(
            'mode',
            'static'
        )
            ->where(
                'aktif',
                true
            )
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Buat QR jika belum ada
        |--------------------------------------------------------------------------
        */

        if (!$qrPermanen) {

            QrCode::create([

                'token' =>
                    Str::random(64),

                /*
                | QR sekarang khusus Apel Pagi.
                */
                'tipe' =>
                    'apel',

                'mode' =>
                    'static',

                'expired_at' =>
                    null,

                'aktif' =>
                    true,

            ]);
        }


        return redirect()
            ->route('admin.qrcode.index')
            ->with(
                'success',
                'QR Code permanen Apel Pagi berhasil dibuat.'
            );
    }
}
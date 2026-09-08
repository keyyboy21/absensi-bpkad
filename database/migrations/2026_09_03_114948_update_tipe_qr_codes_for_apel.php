<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Tambahkan tipe QR Apel
        |--------------------------------------------------------------------------
        |
        | Sebelumnya QR digunakan untuk absensi masuk / pulang.
        | Sekarang QR digunakan khusus untuk absensi Apel Pagi.
        |
        */

        DB::statement("
            ALTER TABLE qr_codes
            MODIFY tipe ENUM(
                'masuk',
                'pulang',
                'apel'
            ) NOT NULL
        ");
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Kembalikan QR Apel ke Masuk terlebih dahulu
        |--------------------------------------------------------------------------
        */

        DB::table('qr_codes')
            ->where('tipe', 'apel')
            ->update([
                'tipe' => 'masuk'
            ]);


        /*
        |--------------------------------------------------------------------------
        | Kembalikan ENUM seperti sebelumnya
        |--------------------------------------------------------------------------
        */

        DB::statement("
            ALTER TABLE qr_codes
            MODIFY tipe ENUM(
                'masuk',
                'pulang'
            ) NOT NULL
        ");
    }
};
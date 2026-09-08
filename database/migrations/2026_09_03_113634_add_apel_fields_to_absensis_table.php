<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Tambahkan Kolom Ketidakhadiran Apel
        |--------------------------------------------------------------------------
        */

        Schema::table('absensis', function (Blueprint $table) {

            $table->string('alasan_tidak_hadir')
                ->nullable()
                ->after('status');

            $table->text('keterangan')
                ->nullable()
                ->after('alasan_tidak_hadir');
        });


        /*
        |--------------------------------------------------------------------------
        | Tambahkan Status Baru
        |--------------------------------------------------------------------------
        |
        | Status sebelumnya:
        | hadir, terlambat, izin, sakit, alpha
        |
        | Ditambahkan:
        | dinas_luar, lainnya
        |
        */

        DB::statement("
            ALTER TABLE absensis
            MODIFY status ENUM(
                'hadir',
                'terlambat',
                'izin',
                'sakit',
                'dinas_luar',
                'lainnya',
                'alpha'
            ) DEFAULT 'hadir'
        ");
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Kembalikan Status Lama
        |--------------------------------------------------------------------------
        */

        DB::statement("
            ALTER TABLE absensis
            MODIFY status ENUM(
                'hadir',
                'terlambat',
                'izin',
                'sakit',
                'alpha'
            ) DEFAULT 'hadir'
        ");


        /*
        |--------------------------------------------------------------------------
        | Hapus Kolom Tambahan
        |--------------------------------------------------------------------------
        */

        Schema::table('absensis', function (Blueprint $table) {

            $table->dropColumn([
                'alasan_tidak_hadir',
                'keterangan',
            ]);
        });
    }
};
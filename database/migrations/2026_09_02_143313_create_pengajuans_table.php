<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuans', function (Blueprint $table) {

            $table->id();

            // Pegawai yang mengajukan
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Jenis pengajuan
            $table->enum('jenis', [
                'izin',
                'sakit'
            ]);

            // Periode izin / sakit
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            // Alasan / keterangan pegawai
            $table->text('keterangan');

            // Bukti pendukung, misalnya surat dokter
            $table->string('bukti')->nullable();

            // Status persetujuan admin
            $table->enum('status', [
                'menunggu',
                'disetujui',
                'ditolak'
            ])->default('menunggu');

            // Catatan dari admin jika diperlukan
            $table->text('catatan_admin')->nullable();

            // Waktu ketika pengajuan diproses admin
            $table->timestamp('diproses_pada')->nullable();

            $table->timestamps();

            // Membantu pencarian pengajuan berdasarkan pegawai/periode
            $table->index([
                'user_id',
                'tanggal_mulai',
                'tanggal_selesai'
            ]);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};
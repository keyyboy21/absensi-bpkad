<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengajuan extends Model
{
    /**
     * Kolom yang boleh diisi melalui mass assignment.
     */
    protected $fillable = [
        'user_id',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'bukti',
        'status',
        'catatan_admin',
        'diproses_pada',
    ];


    /**
     * Konversi tipe data otomatis.
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'diproses_pada' => 'datetime',
        ];
    }


    /**
     * Relasi pengajuan dengan pegawai.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
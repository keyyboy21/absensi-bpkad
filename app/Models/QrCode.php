<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $fillable = [
        'token',
        'tipe',
        'mode',
        'expired_at',
        'aktif',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'aktif' => 'boolean',
    ];
}
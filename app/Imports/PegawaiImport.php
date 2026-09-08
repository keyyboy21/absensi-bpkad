<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PegawaiImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if (
                empty($row['nip_pegawai']) ||
                empty($row['nama_pegawai'])
            ) {
                continue;
            }

            $nip = trim((string) $row['nip_pegawai']);

            $pegawai = User::where('nip', $nip)
                ->where('role', 'pegawai')
                ->first();

            if ($pegawai) {

                /*
                |--------------------------------------------------------------------------
                | NIP Sudah Ada
                |--------------------------------------------------------------------------
                |
                | Update data pegawai tanpa mengganti password.
                |
                */

                $pegawai->update([
                    'name' => $row['nama_pegawai'],
                    'jabatan' => $row['jabatan'] ?? null,
                    'bidang' => $row['bidang'] ?? null,
                ]);

            } else {

                /*
                |--------------------------------------------------------------------------
                | Pegawai Baru
                |--------------------------------------------------------------------------
                */

                User::create([
                    'nip' => $nip,
                    'name' => $row['nama_pegawai'],
                    'email' => null,
                    'jabatan' => $row['jabatan'] ?? null,
                    'bidang' => $row['bidang'] ?? null,
                    'role' => 'pegawai',
                    'status' => 'aktif',
                    'password' => 'bpkad123',
                ]);
            }
        }
    }
}
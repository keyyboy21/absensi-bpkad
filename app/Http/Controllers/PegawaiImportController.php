<?php

namespace App\Http\Controllers;

use App\Imports\PegawaiImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PegawaiImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:5120',
            ],
        ]);

        Excel::import(
            new PegawaiImport(),
            $request->file('file')
        );

        return redirect()
            ->route('admin.pegawai.index')
            ->with(
                'success',
                'Data pegawai berhasil diimport.'
            );
    }
}
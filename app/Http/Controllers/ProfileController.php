<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Halaman Profile
    |--------------------------------------------------------------------------
    */

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Update Profile
    |--------------------------------------------------------------------------
    */

    public function update(
        ProfileUpdateRequest $request
    ): RedirectResponse {

        $user = $request->user();


        /*
        |--------------------------------------------------------------------------
        | Update Data Yang Sudah Divalidasi
        |--------------------------------------------------------------------------
        */

        $user->fill(
            $request->validated()
        );


        /*
        |--------------------------------------------------------------------------
        | Reset Verifikasi Email Jika Email Admin Berubah
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'admin' &&
            $user->isDirty('email')
        ) {

            $user->email_verified_at = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Simpan
        |--------------------------------------------------------------------------
        */

        $user->save();


        return Redirect::route(
            'profile.edit'
        )->with(
            'status',
            'profile-updated'
        );
    }
}
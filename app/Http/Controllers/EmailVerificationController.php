<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    // Menampilkan halaman notifikasi verifikasi
    public function show(Request $request)
    {
        return view('auth.verify');
    }

    // Menangani verifikasi email
    public function verify(EmailVerificationRequest $request)
    {
        // Verifikasi email pengguna
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/home');
        }

        // Tandai email pengguna sebagai sudah terverifikasi
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        alert()->success('Berhasil!', 'Verifikasi email berhasil!');
        // Redirect pengguna setelah verifikasi
        return redirect()->route('home');
    }

    // Mengirim ulang link verifikasi email
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/home');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RedirectIfNotNewUserAndNotVerified
{
    public function handle(Request $request, Closure $next)
    {
        $newUser   = session('new_user');
        $newUserId = session('new_user_id');
        $user      = $newUserId !== null ? User::find($newUserId) : null;

        if ($newUser && $user) {
            if ($user->is_active === 0 && $user->status === 'process') {
                session()->flash('status', '<span class="badge text-bg-warning">Process</span>');
                session()->flash('message', 'Akun kamu berhasil dibuat, namun kami perlu data file CV yang kamu upload untuk keperluan verifikasi oleh admin. Mohon maaf atas penantian dan terima kasih.');
                session()->flash('logo', '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-info-circle-fill text-info" style="margin: 2rem 0;" viewBox="0 0 16 16"><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/></svg>');
            }

            if ($user->is_active === 1 && $user->status === 'approved') {
                session()->flash('status', '<span class="badge text-bg-success">Approved</span>');
                session()->flash('message', 'Akun kamu berhasil diverifikasi oleh admin sebagai penulis. Sekarang, kamu dapat <a href="' . route('login') . '" class="text-login">masuk</a> ke sistem dan menikmati berbagai fitur yang tersedia.');
                session()->flash('logo', '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-check-circle-fill text-success" style="margin: 2rem 0;" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg>');
            }

            if ($user->is_active === 0 && $user->status === 'rejected') {
                session()->flash('status', '<span class="badge text-bg-danger">Rejected</span>');
                session()->flash('message', 'Mohon maaf, akun kamu tidak lolos verifikasi kami karena data file CV kamu tidak valid. Silahkan <a href="' . route('register') . '" class="text-register">daftar</a> kembali jika ingin mencoba lagi.');
                session()->flash('logo', '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-x-circle-fill text-danger" style="margin: 2rem 0;" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/></svg>');
            }
        } else abort(404);

        return $next($request);
    }
}

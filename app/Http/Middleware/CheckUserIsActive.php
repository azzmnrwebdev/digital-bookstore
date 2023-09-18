<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->is_active) {
            Auth::logout();
            return redirect('/login')->with('errorIsActive', 'Akun Anda telah dinonaktifkan, sistem kami mendeteksi adanya tindakan ilegal.  Silakan hubungi <a href="https://wa.me/6283836903996?text=Maaf%2C%20bisa%20saya%20tahu%20kenapa%20akun%20saya%20dinonaktifkan%20karena%20tindakan%20ilegal%3F" target="_blank">administrator</a> lebih lanjut.');
        }

        return $next($request);
    }
}

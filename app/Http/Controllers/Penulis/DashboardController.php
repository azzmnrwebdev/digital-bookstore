<?php

namespace App\Http\Controllers\Penulis;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('penulis.page.dashboard');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('penulis.profile', compact('user'));
    }
}

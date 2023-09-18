<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $testimoni = Testimonial::with('user')->where('users_id', $user->id)->first();
        return view('home.page.testimonial', compact('testimoni'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'review' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Testimonial::create([
            'users_id'  => $user->id,
            'review'    => $request->input('review'),
        ]);

        return redirect()->back()->with('success', 'Testimonial sent successfully.');
    }
}

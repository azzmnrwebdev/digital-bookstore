<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    public function updateReview(Request $request, $id)
    {
        $rating = Rating::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'rating' => 'required|numeric',
            'review' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $rating->update([
            'rating' => $request->input('rating'),
            'review' => $request->input('review')
        ]);

        return redirect()->back()->with('success', 'Review updated successfully.');
    }

    public function storeReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|numeric',
            'review' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Rating::create([
            'ebooks_id' => $request->input('ebooks_id'),
            'users_id'  => Auth::id(),
            'rating'    => $request->input('rating'),
            'review'    => $request->input('review'),
        ]);

        return redirect()->back()->with('success', 'Review sent successfully.');
    }
}

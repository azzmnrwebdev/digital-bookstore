<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');
        $query = Rating::with('user', 'ebook');

        if (!empty($search)) {
            $query->whereHas('user', function ($subquery) use ($search) {
                $subquery->where('fullname', 'LIKE', '%' . $search . '%');
            })->orWhereHas('ebook', function ($subquery) use ($search) {
                $subquery->where('title', 'LIKE', '%' . $search . '%');
            });
        }

        $ratings = $query->orderBy('created_at', 'desc')->orderBy('updated_at', 'desc')->paginate(10);
        return view('admin.page.managerating.index', compact('ratings', 'search'));
    }

    public function destroy(Rating $managerating)
    {
        $managerating->delete();
        return redirect()->back()->with('success', 'Ulasan pengguna berhasil dihapus.');
    }
}

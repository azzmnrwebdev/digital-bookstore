<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');
        $query = Testimonial::query();

        if (!empty($search)) {
            $query->whereHas('user', function ($subquery) use ($search) {
                $subquery->where('fullname', 'LIKE', '%' . $search . '%');
            });
        }

        $testimonials = $query->orderBy('created_at', 'desc')->orderBy('updated_at', 'desc')->paginate(10);
        return view('admin.page.managetestimonial.index', compact('testimonials', 'search'));
    }

    public function destroy(Testimonial $managetestimonial)
    {
        $managetestimonial->delete();
        return redirect()->back()->with('success', 'Testimoni pengguna berhasil dihapus.');
    }
}

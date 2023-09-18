<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Ebook;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Rating;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EbookController extends Controller
{
    public function index()
    {
        $ebooks = Ebook::with('categories', 'authors')->orderByDesc('created_at')->get();

        $ebookCounts = [];
        $averageRatings = [];

        foreach ($ebooks as $ebook) {
            $count = $ebook->orderDetails()->whereHas('order', function ($query) {
                $query->where('payment_status', 'Approved');
            })->sum('quantity');

            $ebookCounts[$ebook->id] = $count;

            $ratings = $ebook->ratings->pluck('rating');

            if ($ratings->isEmpty()) {
                $averageRating = 0;
            } else {
                $averageRating = $ratings->avg();
            }

            $averageRatings[$ebook->id] = $averageRating;
        }

        return view('home.page.ebook', compact('ebooks', 'ebookCounts', 'averageRatings'));
    }

    public function show($slug)
    {
        $ebook = Ebook::with('categories', 'authors', 'ratings')->where('slug', $slug)->firstOrFail();

        $ebookCount = $ebook->orderDetails()->whereHas('order', function ($query) {
            $query->where('payment_status', 'Approved');
        })->where('ebooks_id', $ebook->id)->sum('quantity');

        $rating = $ebook->ratings->where('ebooks_id', $ebook->id)->pluck('rating');
        $averageRating = 0;

        if ($rating->isEmpty()) {
            $averageRating = 0;
        } else {
            $averageRating = $rating->avg();
        }

        return view('home.page.detail-ebook', compact('ebook', 'ebookCount', 'averageRating'));
    }

    public function downloadFree(Request $request)
    {
        $user = Auth::user();
        $idPesanan = Str::uuid();
        $ebookId = $request->input('ebooks_id');

        $existingOrder = Order::where('users_id', $user->id)->whereHas('orderDetails', function ($query) use ($ebookId) {
            $query->where('ebooks_id', $ebookId);
        })->first();

        if ($existingOrder) {
            return redirect()->back()->with('error', 'Anda hanya diperbolehkan mendownload ebook gratis satu kali.');
        }

        $order = Order::create([
            'users_id'        => $user->id,
            'id_pesanan'      => $idPesanan,
            'name'            => $user->fullname,
            'email'           => $user->email,
            'payment_method'  => null,
            'payment_proof'   => null,
            'payment_status'  => 'Approved'
        ]);

        $order->orderDetails()->create([
            'ebooks_id' => $ebookId,
            'quantity' => 1,
        ]);

        $ebook = Ebook::find($ebookId);

        if ($ebook->password) {
            Notification::create([
                'notifiable_id' => $user->id,
                'notifiable_type' => 'App\Models\User',
                'title' => 'Selamat! Anda Telah Mendapatkan Ebook Gratis',
                'message' => 'Terima kasih telah membeli ebook <strong>' . $ebook->title . '</strong> kami. Anda telah berhasil memperoleh ebook secara gratis. Untuk membuka file PDF, diperlukan password yang terlampir di bawah ini:<br><br>Password: ' . $ebook->password . '<br>Selamat membaca!.'
            ]);
        } else {
            Notification::create([
                'notifiable_id' => $user->id,
                'notifiable_type' => 'App\Models\User',
                'title' => 'Selamat! Anda Telah Mendapatkan Ebook Gratis',
                'message' => 'Terima kasih telah membeli ebook <strong>' . $ebook->title . '</strong> kami. Anda telah berhasil memperoleh ebook secara gratis. Selamat membaca!.'
            ]);
        }

        return response()->download($ebook->pdf);
    }
}

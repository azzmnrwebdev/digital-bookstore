<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Rating;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // get data 10 rekomendasi ebook
        $rekomendasiEbook = Ebook::with('categories', 'authors', 'orderDetails', 'ratings')
            ->select('ebooks.*')
            ->selectRaw('AVG(ratings.rating) AS average_rating')
            ->join('ratings', 'ebooks.id', '=', 'ratings.ebooks_id')
            ->groupBy('ebooks.id')
            ->havingRaw('AVG(ratings.rating) = 5')
            ->orderByDesc('ebooks.created_at')
            ->take(10)
            ->get();

        $rekomendasiEbookCounts = [];
        $rekomendasiEbookAvgRatings = [];

        foreach ($rekomendasiEbook as $ebook) {
            $count = $ebook->orderDetails()->whereHas('order', function ($query) {
                $query->where('payment_status', 'Approved');
            })->sum('quantity');

            $rekomendasiEbookCounts[$ebook->id] = $count;

            $ratings = $ebook->ratings->pluck('rating');
            $averageRating = $ratings->avg();

            $rekomendasiEbookAvgRatings[$ebook->id] = $averageRating;
        }

        // get data 10 best seller ebook
        $bestSeller = Ebook::with('categories', 'authors', 'ratings')
            ->select('ebooks.*')
            ->selectRaw('(SELECT COUNT(*) FROM order_details JOIN orders ON order_details.orders_id = orders.id WHERE order_details.ebooks_id = ebooks.id AND orders.payment_status = "Approved") AS total_sales')
            ->selectRaw('AVG(ratings.rating) AS average_rating')
            ->join('ratings', 'ebooks.id', '=', 'ratings.ebooks_id')
            ->groupBy('ebooks.id')
            ->havingRaw('AVG(ratings.rating) >= 4 AND AVG(ratings.rating) <= 5')
            ->orderByDesc('total_sales')
            ->orderByDesc('average_rating')
            ->get();

        $bestSellerCounts = [];
        $bestSellerAvgRatings = [];

        foreach ($bestSeller as $ebook) {
            $count = $ebook->orderDetails()->whereHas('order', function ($query) {
                $query->where('payment_status', 'Approved');
            })->sum('quantity');

            $bestSellerCounts[$ebook->id] = $count;

            $ratings = $ebook->ratings->pluck('rating');
            $averageRating = $ratings->avg();

            $bestSellerAvgRatings[$ebook->id] = $averageRating;
        }

        // get 5 data testimonial
        $testimonials = Testimonial::filterBadWords()->limit(5)->orderByDesc('created_at')->get();

        return view('home.page.home', compact('rekomendasiEbook', 'rekomendasiEbookCounts', 'rekomendasiEbookAvgRatings', 'bestSeller', 'bestSellerCounts', 'bestSellerAvgRatings', 'testimonials'));
    }

    public function checkLoginStatus()
    {
        $loggedIn = Auth::check();
        return response()->json(['loggedIn' => $loggedIn]);
    }
}

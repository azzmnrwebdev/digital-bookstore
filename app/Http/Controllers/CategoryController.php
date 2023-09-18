<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Rating;

class CategoryController extends Controller
{
    public function index()
    {
        $categories  = Category::orderBy('name', 'asc')->get();
        return view('home.page.category', compact('categories'));
    }

    public function getEbookByCategory($slug)
    {
        $category = Category::with('ebooks')->where('slug', $slug)->first();

        $ebookCounts = [];
        $averageRatings = [];

        foreach ($category->ebooks as $ebook) {
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

        return view('home.page.ebook-category', compact('category', 'ebookCounts', 'averageRatings'));
    }
}

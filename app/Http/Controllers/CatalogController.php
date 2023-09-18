<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Category;
use App\Models\Ebook;
use App\Models\Rating;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $authors = Author::with('user')->get();

        $sort = $request->input('sort');
        $rating = $request->input('rating');
        $search = $request->input('q');
        $status = $request->input('status');
        $authorIds = $request->input('author');
        $categoryIds = $request->input('category');

        $ebooks = Ebook::with('categories', 'authors');

        if (!empty($search)) {
            $ebooks->where(function ($query) use ($search) {
                $query->where('isbn', 'like', "%$search%")
                    ->orWhere('title', 'like', "%$search%");
            });
        }

        if (!empty($status)) {
            $ebooks->where('status', $status);
        }

        if (!empty($sort) && $sort === 'termurah-ke-termahal') {
            $ebooks->where('status', 'paid')->orderByRaw("CAST(price AS UNSIGNED) asc");
        } elseif (!empty($sort) && $sort === 'termahal-ke-termurah') {
            $ebooks->where('status', 'paid')->orderByRaw("CAST(price AS UNSIGNED) desc");
        }

        if (!empty($rating)) {
            $ebooks->whereHas('ratings', function ($query) use ($rating) {
                $query->where('rating', $rating);
            })->withAvg('ratings', 'rating');
        }

        if (!empty($categoryIds)) {
            $categoryArray = explode('-', urldecode($categoryIds));
            $ebooks->whereHas('categories', function ($query) use ($categoryArray) {
                $query->whereIn('id', $categoryArray);
            });
        }

        // if (!empty($authorIds)) {
        //     $authorArray = explode('-', urldecode($authorIds));
        //     $ebooks->whereHas('authors', function ($query) use ($authorArray) {
        //         $query->whereHas('user', function ($query) use ($authorArray) {
        //             $query->whereIn('username', $authorArray);
        //         });
        //     });
        // }

        $ebooks = $ebooks->orderByDesc('created_at')->get();

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

        return view('home.page.catalog', compact('categories', 'authors', 'ebooks', 'search', 'categoryIds', 'authorIds', 'status', 'sort', 'ebookCounts', 'averageRatings', 'rating'));
    }
}

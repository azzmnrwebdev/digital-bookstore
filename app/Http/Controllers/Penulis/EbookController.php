<?php

namespace App\Http\Controllers\Penulis;

use Carbon\Carbon;
use App\Models\Ebook;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EbookController extends Controller
{
    public function index(Request $request)
    {
        $userLogin = Auth::user();
        $categories = Category::orderBy('name', 'asc')->get();

        $search = $request->input('q');
        $status = $request->input('status');
        $rating = $request->input('rating');
        $categoryIds = $request->input('category');

        $adminAuthorId = $userLogin->author->id;
        $ebooks = Ebook::whereHas('authors', function ($query) use ($adminAuthorId) {
            $query->where('uploaded_by', 'admin')->where('authors.id', $adminAuthorId);
        });

        if (!empty($search)) {
            $ebooks->where(function ($query) use ($search) {
                $query->where('isbn', 'like', "%$search%")
                    ->orWhere('title', 'like', "%$search%");
            });
        }

        if (!empty($categoryIds)) {
            $categoryArray = explode('-', urldecode($categoryIds));
            $ebooks->whereHas('categories', function ($query) use ($categoryArray) {
                $query->whereIn('id', $categoryArray);
            });
        }

        if (!empty($status)) {
            $ebooks->where('status', $status);
        }

        if (!empty($rating)) {
            $ebooks->whereHas('ratings', function ($query) use ($rating) {
                $query->where('rating', $rating);
            })->withAvg('ratings', 'rating');
        }

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

        return view('penulis.page.manageebook.index', compact('ebooks', 'categories', 'search', 'categoryIds', 'status', 'ebookCounts', 'averageRatings', 'rating'));
    }

    public function kontribusi(Request $request)
    {
        $userLogin = Auth::user();
        $categories = Category::orderBy('name', 'asc')->get();

        $search = $request->input('q');
        $status = $request->input('status');
        $rating = $request->input('rating');
        $categoryIds = $request->input('category');

        $adminAuthorId = $userLogin->author->id;
        $ebooks = Ebook::whereHas('authors', function ($query) use ($adminAuthorId) {
            $query->where('uploaded_by', null)->where('authors.id', $adminAuthorId);
        });

        if (!empty($search)) {
            $ebooks->where(function ($query) use ($search) {
                $query->where('isbn', 'like', "%$search%")
                    ->orWhere('title', 'like', "%$search%");
            });
        }

        if (!empty($categoryIds)) {
            $categoryArray = explode('-', urldecode($categoryIds));
            $ebooks->whereHas('categories', function ($query) use ($categoryArray) {
                $query->whereIn('id', $categoryArray);
            });
        }

        if (!empty($status)) {
            $ebooks->where('status', $status);
        }

        if (!empty($rating)) {
            $ebooks->whereHas('ratings', function ($query) use ($rating) {
                $query->where('rating', $rating);
            })->withAvg('ratings', 'rating');
        }

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

        return view('penulis.page.manageebook.kontribusi', compact('ebooks', 'categories', 'search', 'categoryIds', 'status','ebookCounts', 'averageRatings', 'rating'));
    }

    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $authors = Author::whereHas('user', function ($query) {
            $query->where('role', 'penulis')->where('is_active', 1);
        })->get();

        $data = [
            'categories' => $categories,
            'authors' => $authors,
        ];

        return view('penulis.page.manageebook.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'isbn'          => 'nullable|numeric|min:13|unique:ebooks,isbn',
            'title'         => 'required|string|unique:ebooks,title',
            'status'        => 'required',
            'category_ids'  => 'required',
            'author_ids'    => 'required',
            'description'   => 'required',
            'thumbnail'     => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'pdf'           => 'required|file|mimes:pdf|max:10240',
        ], [
            'category_ids.required' => 'The category field is required.',
            'author_ids.required'   => 'The author field is required.',
        ]);

        if ($request->has('status') && $request->input('status') === 'paid') {
            $validator->sometimes('price', 'required|numeric', function ($input) {
                return $input->status === 'paid';
            });
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $ebook = Ebook::create([
            'isbn'        => $request->input('isbn'),
            'title'       => $request->input('title'),
            'slug'        => Str::slug($request->input('title')),
            'status'      => $request->input('status'),
            'price'       => $request->input('price'),
            'description' => $request->input('description'),
            'thumbnail'   => $this->uploadFile('thumbnail-', Str::slug($request->input('title')), $request->file('thumbnail'), 'ebook/thumbnail'),
            'pdf'         => $this->uploadFile('ebook-', Str::slug($request->input('title')), $request->file('pdf'), 'ebook/pdf'),
            'password'    => $request->input('password'),
        ]);

        $categoryIds = $request->input('category_ids');
        $ebook->categories()->attach($categoryIds);

        $authorIds = $request->input('author_ids');
        $loggedInUserAuthor = auth()->user()->author;

        if (!in_array($loggedInUserAuthor->id, $authorIds)) {
            $ebook->authors()->attach($loggedInUserAuthor->id, ['uploaded_by' => 'admin']);
        } else {
            $ebook->authors()->attach($loggedInUserAuthor->id, ['uploaded_by' => 'admin']);
        }

        $otherAuthors = array_diff($authorIds, [$loggedInUserAuthor->id]);
        $ebook->authors()->attach($otherAuthors);

        $otherAuthors = $ebook->authors()->where('authors.id', '!=', $loggedInUserAuthor->id)->get();
        foreach ($otherAuthors as $author) {
            Notification::create([
                'notifiable_id' => $author->id,
                'notifiable_type' => 'App\Models\Author',
                'title' => 'Anda telah ditambahkan sebagai kontribusi ebook',
                'message' => 'Ebook dengan judul <strong>' . $ebook->title . '</strong> ini telah diunggah oleh penulis <strong>' . $loggedInUserAuthor->user->fullname . '</strong>',
            ]);
        }

        return redirect(route('penulis.ebook.index'))->with('success', 'Data ebook berhasil disimpan.');
    }

    public function show($slug)
    {
        $ebook = Ebook::with('categories', 'authors')->where('slug', $slug)->firstOrFail();
        $adminAuthor = $ebook->authors()->wherePivot('uploaded_by', 'admin')->first();
        $loggedInUserAuthor = auth()->user()->author;

        if ($adminAuthor && $adminAuthor->users_id === $loggedInUserAuthor->users_id) {
            return view('penulis.page.manageebook.show', compact('ebook'));
        } elseif ($ebook->authors->contains($loggedInUserAuthor)) {
            return view('penulis.page.manageebook.show', compact('ebook'));
        } else {
            abort(403);
        }
    }

    public function edit($slug)
    {
        $ebook = Ebook::with('categories', 'authors')->where('slug', $slug)->firstOrFail();
        $adminAuthor = $ebook->authors()->wherePivot('uploaded_by', 'admin')->first();
        $loggedInUserAuthor = auth()->user()->author;

        if ($adminAuthor && $adminAuthor->users_id === $loggedInUserAuthor->users_id) {
            $categories = Category::all();
            $authors = Author::whereHas('user', function ($query) {
                $query->where('role', 'penulis')->where('is_active', 1);
            })->get();

            $data = [
                'ebook' => $ebook,
                'categories' => $categories,
                'authors' => $authors,
            ];

            return view('penulis.page.manageebook.edit', $data);
        } else {
            abort(403);
        }
    }

    public function update(Request $request, $id)
    {
        $ebook = Ebook::findOrFail($id);
        $loggedInUserAuthor = auth()->user()->author;

        $validator = Validator::make($request->all(), [
            'isbn'          => 'nullable|numeric|min:13|unique:ebooks,isbn,' . $id,
            'title'         => 'required|string|unique:ebooks,title,' . $id,
            'status'        => 'required',
            'category_ids'  => 'required',
            'author_ids'    => 'required',
            'description'   => 'required',
            'thumbnail'     => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'pdf'           => 'nullable|file|mimes:pdf|max:10240',
        ], [
            'category_ids.required' => 'The category field is required.',
            'author_ids.required'   => 'The author field is required.',
        ]);

        if ($request->has('status') && $request->input('status') === 'paid') {
            $validator->sometimes('price', 'required|numeric', function ($input) {
                return $input->status === 'paid';
            });
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('thumbnail')) {
            if (!empty($ebook->thumbnail) && Storage::disk('public')->exists(Str::after($ebook->thumbnail, 'storage/'))) {
                Storage::disk('public')->delete(Str::after($ebook->thumbnail, 'storage/'));
            }

            $thumbnail = $this->uploadFile('thumbnail-', Str::slug($request->input('title')), $request->file('thumbnail'), 'ebook/thumbnail');
            $ebook->thumbnail = $thumbnail;
        }

        if ($request->hasFile('pdf')) {
            if (!empty($ebook->pdf) && Storage::disk('public')->exists(Str::after($ebook->pdf, 'storage/'))) {
                Storage::disk('public')->delete(Str::after($ebook->pdf, 'storage/'));
            }

            $pdf = $this->uploadFile('ebook-', Str::slug($request->input('title')), $request->file('pdf'), 'ebook/pdf');
            $ebook->pdf = $pdf;
        }

        $ebook->update([
            'isbn'        => $request->input('isbn'),
            'title'       => $request->input('title'),
            'slug'        => Str::slug($request->input('title')),
            'status'      => $request->input('status'),
            'price'       => $request->input('price'),
            'description' => $request->input('description'),
            'password'    => $request->input('password')
        ]);

        $categoryIds = $request->input('category_ids');
        $ebook->categories()->sync($categoryIds);

        $authorIds = $request->input('author_ids');
        if (!in_array($loggedInUserAuthor->id, $authorIds) && $loggedInUserAuthor->users_id === $ebook->authors()->wherePivot('uploaded_by', 'admin')->first()->users_id) {
            $validator->errors()->add('author_ids', 'You are prohibited from deleting yourself.');
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $removedAuthors = $ebook->authors()->whereNotIn('id', $authorIds)->get();
            $ebook->authors()->detach($removedAuthors->pluck('id'));
            foreach ($removedAuthors as $removedAuthor) {
                Notification::create([
                    'notifiable_id'   => $removedAuthor->id,
                    'notifiable_type' => 'App\Models\Author',
                    'title'           => 'Anda telah dikeluarkan dari kontribusi ebook',
                    'message'         => 'Penulis <strong>' . $loggedInUserAuthor->user->fullname . '</strong> telah mengeluarkan Anda dari kontribusi ebook  <strong>' . $ebook->title . '</strong>'
                ]);
            }

            if ($request->has('author_ids')) {
                $newAuthorIds = array_diff($authorIds, $ebook->authors->pluck('id')->toArray());
                $ebook->authors()->attach($newAuthorIds);
                foreach ($newAuthorIds as $newAuthorId) {
                    Notification::create([
                        'notifiable_id'   => $newAuthorId,
                        'notifiable_type' => 'App\Models\Author',
                        'title'           => 'Anda telah ditambahkan sebagai kontribusi ebook',
                        'message'         => 'Penulis <strong>' . $loggedInUserAuthor->user->fullname . '</strong> telah menambahkan Anda sebagai kontribusi ebook  <strong>' . $ebook->title . '</strong>'
                    ]);
                }
            }

            $ebook->authors()->sync($authorIds);
        }

        return redirect(route('penulis.ebook.index'))->with('success', 'Data ebook berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ebook = Ebook::findOrFail($id);

        $ebook->delete();

        $uploadedBy = $ebook->authors()->wherePivot('uploaded_by', 'admin')->first();
        $kontribusiAuthors = $ebook->authors()->wherePivot('uploaded_by', null)->get();

        foreach ($kontribusiAuthors as $author) {
            Notification::create([
                'notifiable_id'   => $author->id,
                'notifiable_type' => 'App\Models\Author',
                'title'           => 'Anda telah dihapus dari kontribusi ebook',
                'message'         => 'Ebook dengan judul <strong>' . $ebook->title . '</strong> ini telah dihapus oleh penulis <strong>' . $uploadedBy->user->fullname . '</strong>',
            ]);
        }

        return redirect(route('penulis.ebook.index'))->with('success', 'Data ebook berhasil dihapus.');
    }

    private function uploadFile($name, $slug, $file, $path)
    {
        $fileName = $name . $slug . '-' . str_replace([' ', '-', ':'], '', Carbon::now('Asia/Jakarta')->format('d-m-Y H:i:s')) . '-' . sha1(mt_rand(1, 999999) . microtime()) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs($path, $fileName, 'public');
        return 'storage/' . $filePath;
    }
}

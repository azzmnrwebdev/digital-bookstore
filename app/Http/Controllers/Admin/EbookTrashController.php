<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ebook;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class EbookTrashController extends Controller
{
    public function index()
    {
        $ebooksTrash = Ebook::with('authors')->onlyTrashed()->paginate(10);
        $getAuthorName = [];

        foreach ($ebooksTrash as $ebook) {
            $adminAuthor = $ebook->authors()->wherePivot('uploaded_by', 'admin')->first();
            $getAuthorName[$ebook->id] = $adminAuthor->user->fullname;
        }

        return view('admin.page.manageebooktrash.index', compact('ebooksTrash', 'getAuthorName'));
    }

    public function show($slug)
    {
        //
    }

    // restore data
    public function restore(Ebook $manageebooktrash)
    {
        $manageebooktrash->restore();
        return redirect()->back()->with('success', 'Data has been restored successfully');
    }

    // delete permanent
    public function forceDelete(Ebook $manageebooktrash)
    {
        if (!empty($manageebooktrash->thumbnail) && Storage::disk('public')->exists(Str::after($manageebooktrash->thumbnail, 'storage/'))) {
            Storage::disk('public')->delete(Str::after($manageebooktrash->thumbnail, 'storage/'));
        }

        if (!empty($manageebooktrash->pdf) && Storage::disk('public')->exists(Str::after($manageebooktrash->pdf, 'storage/'))) {
            Storage::disk('public')->delete(Str::after($manageebooktrash->pdf, 'storage/'));
        }

        $manageebooktrash->forceDelete();
        return redirect()->back()->with('success', 'Data has been permanently deleted');
    }
}

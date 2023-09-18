<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');
        $query  = Category::query();

        if (!empty($search)) $query->where('name', 'LIKE', '%' . $search . '%');
        $categories = $query->orderBy('created_at', 'desc')->orderBy('updated_at', 'desc')->paginate(10);
        return view('admin.page.managecategory.index', compact('categories', 'search'));
    }

    public function create()
    {
        return view('admin.page.managecategory.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:categories,name',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Category::create([
            'name'        => $request->input('name'),
            'slug'        => Str::slug($request->input('name')),
            'description' => $request->input('description'),
        ]);

        return redirect(route('managecategory.index'))->with('success', 'Data kategori berhasil disimpan');
    }

    public function show($slug)
    {
        $managecategory = Category::where('slug', $slug)->firstOrFail();
        return view('admin.page.managecategory.show', compact('managecategory'));
    }

    public function edit($slug)
    {
        $managecategory = Category::where('slug', $slug)->firstOrFail();
        return view('admin.page.managecategory.edit', compact('managecategory'));
    }

    public function update(Request $request, Category $managecategory)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:categories,name,' . $managecategory->id,
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $managecategory->update([
            'name'        => $request->input('name'),
            'slug'        => Str::slug($request->input('name')),
            'description' => $request->input('description'),
        ]);

        return redirect(route('managecategory.index'))->with('success', 'Data kategori berhasil diperbarui.');
    }

    public function destroy(Category $managecategory)
    {
        if ($managecategory->ebooks()->exists()) {
            return redirect()->back()->with('error', 'Kategori sedang digunakan dalam data buku dan tidak dapat dihapus.');
        } else {
            $managecategory->delete();
            return redirect()->back()->with('success', 'Data kategori berhasil dihapus.');
        }
    }
}

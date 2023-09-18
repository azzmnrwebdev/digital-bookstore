<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $ebookId = $request->input('ebooks_id');

        $ebook = Ebook::find($ebookId);
        $adminAuthor = $ebook->authors()->wherePivot('uploaded_by', 'admin')->first();
        $otherAuthors = $ebook->authors()->wherePivot('uploaded_by', null)->pluck('users_id');

        if ($adminAuthor && $adminAuthor->users_id === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak diizinkan untuk membeli ebook anda sendiri');
        }

        if ($otherAuthors->contains($user->id)) {
            return redirect()->back()->with('error', 'Anda tidak diizinkan untuk membeli ebook anda sendiri');
        }

        $existingCart = Cart::where('users_id', $user->id)->where('ebooks_id', $ebookId)->first();
        if ($existingCart) {
            return redirect()->back()->with('error', 'Data ini sudah ditambahkan dalam keranjang.');
        }

        Cart::create([
            'users_id' => $user->id,
            'ebooks_id' => $ebookId,
            'quantity' => 1
        ]);

        return redirect(route('cart.index'))->with('success', 'Data berhasil ditambahkan ke keranjang.');
    }

    public function index()
    {
        $user = Auth::user();
        $carts = Cart::with('ebook')->where('users_id', $user->id)->whereHas('ebook', function ($query) {
            $query->whereNull('deleted_at');
        })->get();
        return view('home.page.cart', compact('carts'));
    }

    public function destroy($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();
        return redirect(route('cart.index'))->with('success', 'Data berhasil dihapus.');
    }

    public function cartCount()
    {
        $user = Auth::user();
        $cartCount = Cart::where('users_id', $user->id)->whereHas('ebook', function ($query) {
            $query->whereNull('deleted_at');
        })->count();
        return response()->json(['count' => $cartCount], 200);
    }
}

<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Ebook;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function checkout()
    {
        $user = Auth::user();
        $cartCount = Cart::where('users_id', $user->id)->count();

        if ($cartCount <= 0) {
            return redirect(route('cart.index'))->with('error', 'Gagal mengakses halaman checkout karena Anda tidak memiliki data keranjang.');
        }

        $carts = Cart::with('ebook')->where('users_id', $user->id)->get();
        $jumlahTagihan = 0;

        foreach ($carts as $cart) {
            $jumlahTagihan += $cart->ebook->price * $cart->quantity;
        }

        return view('home.page.checkout', compact('jumlahTagihan'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $idPesanan = Str::uuid();
        $carts = Cart::where('users_id', $user->id)->get();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'payment_method' => 'required|in:m_banking,e_wallet',
            'payment_proof' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ], [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'payment_method.required' => 'The payment method field is required.',
            'payment_method.in' => 'The selected payment method is invalid.',
            'payment_proof.required' => 'The payment proof field is required.',
            'payment_proof.image' => 'The payment proof must be an image.',
            'payment_proof.mimes' => 'The payment proof must be a file of type: png, jpg, jpeg.',
            'payment_proof.max' => 'The payment proof may not be greater than 2048 kilobytes.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $paymentProofName = $user->username . $idPesanan . '-' . str_replace([' ', '-', ':'], '', Carbon::now('Asia/Jakarta')->format('d-m-Y H:i:s')) . '-' . sha1(mt_rand(1, 999999) . microtime()) . '.' . $request->file('payment_proof')->getClientOriginalExtension();
        $paymentProofPath = $request->file('payment_proof')->storeAs('bukti_pembayaran', $paymentProofName, 'public');
        $paymentProof = 'storage/' . $paymentProofPath;

        $order = Order::create([
            'users_id'        => $user->id,
            'id_pesanan'      => $idPesanan,
            'name'            => $request->input('name'),
            'email'           => $request->input('email'),
            'payment_method'  => $request->input('payment_method'),
            'payment_proof'   => $paymentProof,
            'payment_status'  => 'Process'
        ]);

        $authors = [];
        $ebookTitles = [];
        $orderId = $order->id;

        foreach ($carts as $cart) {
            $orderDetail = $order->orderDetails()->create([
                'ebooks_id' => $cart->ebooks_id,
                'quantity' => $cart->quantity,
            ]);

            $ebook = Ebook::find($cart->ebooks_id);
            $ebookAuthors = $ebook->authors()->get();
            $authors = array_merge($authors, $ebookAuthors->toArray());

            $ebookTitles[] = $orderDetail->ebook->title;
        }

        $ebookTitle = implode(', ', $ebookTitles);

        Cart::where('users_id', $user->id)->delete();

        // kirim notifikasi ke user yang melakukan pemesanan
        Notification::create([
            'notifiable_id' => $user->id,
            'notifiable_type' => 'App\Models\User',
            'title' => 'Pembelian Ebook Berhasil',
            'message' => 'Terima kasih telah melakukan pembelian ebook <strong>' . $ebookTitle . '</strong>. Bukti pembayaran Anda sedang diverifikasi oleh tim admin kami. Harap tunggu hingga 2 jam untuk proses verifikasi.'
        ]);

        // kirim notifikasi ke admin bahwa pembayaran harus di verifikasi
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'notifiable_id' => $admin->id,
                'notifiable_type' => 'App\Models\User',
                'title' => 'Pemberitahuan Pesanan Baru',
                'message' => "Halo " . $admin->fullname . ",<br><br>Kami ingin memberitahu Anda bahwa terdapat pesanan baru yang memerlukan verifikasi pembayaran dengan nomor ID Pesanan: <strong>" . $order->id_pesanan . "</strong>. Mohon segera melakukan pengecekan dan verifikasi pembayaran untuk melanjutkan proses pesanan.<br><br>Terima kasih atas perhatian dan kerja sama Anda.<br><br>Salam,<br>Tim RuangLiterasi"
            ]);
        }

        return redirect(route('order.index'))->with('success', 'Pembelian Ebook Berhasil');
    }

    public function index()
    {
        $user = Auth::user();
        $orderProcess = Order::with('orderDetails')->where('users_id', $user->id)->where('payment_status', 'Process')->orderByDesc('created_at')->get();
        return view('home.page.order', compact('orderProcess'));
    }

    public function process()
    {
        $user = Auth::user();
        $orderProcess = Order::with('orderDetails')->where('users_id', $user->id)->where('payment_status', 'Process')->orderByDesc('created_at')->get();
        return view('home.page.order', compact('orderProcess'));
    }

    public function approved()
    {
        $user = Auth::user();
        $orderApproved = Order::with('orderDetails')->where('users_id', $user->id)->where('payment_status', 'Approved')->orderByDesc('created_at')->get();

        foreach ($orderApproved as $approved) {
            foreach ($approved->orderDetails as $orderDetail) {
                $userRating = Rating::where('ebooks_id', $orderDetail->ebook->id)->where('users_id', $user->id)->first();
                $orderDetail->ebook->ratings = $userRating;
            }
        }

        return view('home.page.order', compact('orderApproved'));
    }

    public function rejected()
    {
        $user = Auth::user();
        $orderRejected = Order::with('orderDetails')->where('users_id', $user->id)->where('payment_status', 'Rejected')->orderByDesc('created_at')->get();
        return view('home.page.order', compact('orderRejected'));
    }
}

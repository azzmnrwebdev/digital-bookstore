<?php

namespace App\Http\Controllers\Penulis;

use DateTimeZone;
use Carbon\Carbon;
use App\Models\Ebook;
use App\Models\Rating;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $userLogin = Auth::user();
        $adminAuthorId = $userLogin->author->id;

        // Get ebooks and their counts
        $ebooks = Ebook::whereHas('authors', function ($query) use ($adminAuthorId) {
            $query->where('authors.id', $adminAuthorId);
        })->orderByDesc('created_at')->get();

        $ebookCounts = $ebooks->mapWithKeys(function ($ebook) {
            $count = $ebook->orderDetails()->whereHas('order', function ($query) {
                $query->where('payment_status', 'Approved');
            })->sum('quantity');

            return [$ebook->id => $count];
        });

        // Get sales data
        $salesData = [];
        $orders = OrderDetail::whereIn('ebooks_id', $ebooks->pluck('id'))->join('orders', 'order_details.orders_id', '=', 'orders.id')->orderBy('orders.created_at', 'desc')->get();

        foreach ($orders as $order) {
            $ebook = $ebooks->firstWhere('id', $order->ebooks_id);
            $rating = Rating::where('users_id', $order->order->user->id)->where('ebooks_id', $ebook->id)->first();

            $sale = [
                'user' => $order->order->user->fullname,
                'ebook' => $ebook->title,
                'payment_status' => $order->order->payment_status,
                'rating' => $rating ? $rating->rating : null,
                'purchase_date' => $order->order->created_at,
            ];

            $salesData[] = $sale;
        }

        // Get saldo per bulan
        $bulanIni = Carbon::now()->month;
        $ebooksPaid = Ebook::where('status', 'paid')->whereHas('authors', function ($query) use ($adminAuthorId, $bulanIni) {
            $query->where('uploaded_by', 'admin')->where('authors.id', $adminAuthorId);
        })->whereHas('orderDetails.order', function ($query) use ($bulanIni) {
            $query->where('payment_status', 'Approved')->whereMonth('created_at', $bulanIni);
        })->get();

        $saldoPerBulan = $ebooksPaid->sum(function ($ebook) use ($bulanIni) {
            $count = $ebook->orderDetails()->whereHas('order', function ($query) use ($bulanIni) {
                $query->where('payment_status', 'Approved')->whereMonth('created_at', $bulanIni);
            })->count();

            return $ebook->price * $count * 0.9;
        });

        // Last day of month
        $now = Carbon::now(new DateTimeZone('Asia/Jakarta'));
        $is25thDayOfMonth = $now->day == 25;

        // total keseluruhan
        $ebooksPaid = Ebook::where('status', 'paid')->whereHas('authors', function ($query) use ($adminAuthorId) {
            $query->where('uploaded_by', 'admin')->where('authors.id', $adminAuthorId);
        })->whereHas('orderDetails.order', function ($query) {
            $query->where('payment_status', 'Approved');
        })->get();

        $totalKeseluruhan = $ebooksPaid->sum(function ($ebook) {
            $count = $ebook->orderDetails()->whereHas('order', function ($query) {
                $query->where('payment_status', 'Approved');
            })->count();

            return $ebook->price * $count * 0.9;
        });

        // if ($is25thDayOfMonth) {
        //     // jika saldo pendapatan perbulan kurang dari 50ribu
        //     if ($saldoPerBulan < 50000) {
        //         $notificationTitle = "Saldo Perbulan";
        //         $notificationMessage = "Saldo Anda pada bulan ini belum mencapai batas minimum untuk pencairan.";
        //     } else {
        //         $notificationTitle = "Pendapatan Tersedia";
        //         $notificationMessage = "Pendapatan Anda dari penjualan ebook sudah tersedia dan dapat dicairkan.";
        //     }

        //     // Cek apakah notifikasi sudah pernah dibuat sebelumnya
        //     $existingNotification = Notification::where([
        //         'notifiable_id' => $userLogin->id,
        //         'notifiable_type' => 'App\Models\User',
        //         'title' => $notificationTitle,
        //     ])->first();

        //     if (!$existingNotification) {
        //         Notification::create([
        //             'notifiable_id' => $userLogin->id,
        //             'notifiable_type' => 'App\Models\User',
        //             'title' => $notificationTitle,
        //             'message' => $notificationMessage,
        //         ]);
        //     }
        // }

        return view('penulis.page.sales.index', compact('ebooks', 'ebookCounts', 'salesData', 'saldoPerBulan', 'is25thDayOfMonth', 'totalKeseluruhan'));
    }
}

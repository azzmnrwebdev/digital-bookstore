<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Models\Notification;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');
        $paymentMethod = $request->input('payment_method');
        $paymentStatus = $request->input('payment_status');

        $orders = Order::with('orderDetails');

        if (!empty($search)) {
            $orders->where(function ($query) use ($search) {
                $query->where('id_pesanan', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        if (!empty($paymentMethod)) {
            $orders->where('payment_method', $paymentMethod);
        }

        if (!empty($paymentStatus)) {
            $orders->where('payment_status', $paymentStatus);
        }

        $orders = $orders->orderByDesc('id')->paginate(10);

        return view('admin.page.manageorder.index', compact('search', 'paymentMethod', 'paymentStatus', 'orders'));
    }

    public function edit($IDPesanan)
    {
        $order = Order::where('id_pesanan', $IDPesanan)->firstOrFail();
        return view('admin.page.manageorder.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $data  = $request->all();
        $order = Order::findOrFail($id);
        $orderDetails = OrderDetail::with('ebook')->where('orders_id', $order->id)->get();

        if ($order->payment_status === 'Process' && $data['payment_status'] === 'Approved' && $orderDetails->isNotEmpty()) {
            $orderDetails = $order->orderDetails()->get();
            $titleEbooks = [];

            foreach ($orderDetails as $orderDetail) {
                $ebook = Ebook::findOrFail($orderDetail->ebooks_id);
                $authors = $ebook->authors()->get();

                foreach ($authors as $author) {
                    $titleEbooks[$author->id] = $ebook->title;

                    $user = User::findOrFail($author->users_id);
                    $ebookTitle = $titleEbooks[$author->id];

                    Notification::create([
                        'notifiable_id' => $author->id,
                        'notifiable_type' => 'App\Models\Author',
                        'title' => 'Pemberitahuan Penjualan Ebook',
                        'message' => "Halo " . $user->fullname . ",<br><br>Kami ingin memberitahu Anda bahwa ebook Anda dengan judul <strong>" . $ebookTitle . "</strong> baru saja terjual. Terus lanjutkan dengan karya yang baik!<br>Terima kasih atas kontribusi Anda dalam menjual ebook ini. Semoga penjualan ebook Anda terus meningkat dan memberikan manfaat kepada pembaca.<br><br>Salam,<br>Tim RuangLiterasi"
                    ]);
                }
            }

            $ebookTitles = $orderDetails->pluck('ebook.title')->implode(', ');
            Notification::create([
                'notifiable_id' => $order->users_id,
                'notifiable_type' => 'App\Models\User',
                'title' => 'Pembayaran Ebook Terverifikasi',
                'message' => 'Kami dengan senang hati menginformasikan bahwa bukti pembayaran Anda untuk ebook <strong>' . $ebookTitles . '</strong> telah berhasil diverifikasi. Ebook yang telah Anda beli, termasuk ' . $orderDetails->count() . ' buah ebook, telah kami kirimkan ke alamat email Anda. Silakan cek email Anda untuk mengakses ebook yang telah dikirim. Terima kasih!'
            ]);

            // kirim file ebook ke akun user via email
            $data["email"] = $order->email;
            $data["subject"] = "Ebook telah berhasil dikirim";
            $data["body"] = "Terima kasih telah melakukan pembelian ebook pada kami. Berikut adalah ebook yang telah berhasil dikirimkan:";

            $files = [];
            foreach ($orderDetails as $detail) {
                $ebookPath = public_path($detail->ebook->pdf);
                $files[] = $ebookPath;
            }

            Mail::send('mail.send-ebook', $data, function ($message) use ($data, $files) {
                $message->to($data["email"])->subject($data["subject"]);

                foreach ($files as $file) {
                    $message->attach($file);
                }
            });
        }

        if ($order->payment_status === 'Process' && $data['payment_status'] === 'Rejected' && $orderDetails->isNotEmpty()) {
            $ebookTitles = $orderDetails->pluck('ebook.title')->implode(', ');

            Notification::create([
                'notifiable_id' => $order->users_id,
                'notifiable_type' => 'App\Models\User',
                'title' => 'Pembayaran Ebook Ditolak',
                'message' => 'Mohon maaf, bukti pembayaran yang Anda kirimkan untuk ebook <strong>' . $ebookTitles . '</strong> tidak dapat diverifikasi. Harap hubungi tim <a href="https://wa.me/6283836903996" class="text-decoration-none">admin</a> kami untuk bantuan lebih lanjut.'
            ]);
        }

        if ($order->payment_status === 'Approved' && $data['payment_status'] === 'Process') {
            return redirect()->back()->with('error', 'Status pesanan tidak dapat diubah.');
        }

        if ($order->payment_status === 'Approved' && $data['payment_status'] === 'Rejected') {
            return redirect()->back()->with('error', 'Status pesanan tidak dapat diubah.');
        }

        if ($order->payment_status === 'Rejected' && $data['payment_status'] === 'Process') {
            return redirect()->back()->with('error', 'Status pesanan tidak dapat diubah.');
        }

        if ($order->payment_status === 'Rejected' && $data['payment_status'] === 'Approved') {
            return redirect()->back()->with('error', 'Status pesanan tidak dapat diubah.');
        }

        $order->update([
            'payment_status' => $request->input('payment_status'),
        ]);

        return redirect(route('admin.manageorder.index'))->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        if (!empty($order->payment_proof) && Storage::disk('public')->exists(Str::after($order->payment_proof, 'storage/'))) {
            Storage::disk('public')->delete(Str::after($order->payment_proof, 'storage/'));
        }

        $order->delete();
        return redirect(route('admin.manageorder.index'))->with('success', 'Data pesanan berhasil dihapus.');
    }
}

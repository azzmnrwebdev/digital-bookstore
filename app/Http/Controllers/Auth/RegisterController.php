<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Author;
use App\Models\Profile;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'fullname'     => 'required|string|min:5|max:50',
            'username'     => 'required|string|min:5|max:50|unique:users,username',
            'email'        => 'required|string|email|max:50|unique:users,email',
            'role'         => 'required',
            'password'     => 'required|string|min:8|confirmed',
        ]);

        $validator->sometimes('cv', 'required|file|mimes:pdf|max:5120', function ($input) {
            return $input->role === 'penulis';
        });

        if ($validator->fails()) {
            session()->flash('error', $validator->errors()->all());
        }

        return $validator;
    }

    protected function create(array $data)
    {
        $user = User::create([
            'fullname'     => $data['fullname'],
            'username'     => $data['username'],
            'email'        => $data['email'],
            'role'         => $data['role'],
            'password'     => Hash::make($data['password']),
        ]);

        if ($data['role'] === 'penulis') {
            $user->role = 'penulis';
            $user->save();

            Author::create([
                'users_id'   => $user->id,
                'cv'         => $this->storeFile($data['cv'], 'penulis-' . $user->username, 'cv')
            ]);

            Notification::create([
                'notifiable_id' => $user->id,
                'notifiable_type' => 'App\Models\User',
                'title' => 'Selamat Bergabung di RuangLiterasi!',
                'message' => 'Halo <strong>' . $user->fullname . '</strong>, Selamat datang di platform kami! Kami ingin memberi tahu Anda bahwa kami sangat menghargai kehadiran Anda di sini. Jangan ragu untuk menjelajahi fitur-fitur yang tersedia dan jangan sungkan untuk menghubungi kami jika ada yang bisa kami bantu, terima kasih.<br><br> Tim RuangLiterasi'
            ]);

            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'notifiable_id' => $admin->id,
                    'notifiable_type' => 'App\Models\User',
                    'title' => 'Pengguna penulis baru terdaftar',
                    'message' => 'Pengguna penulis baru dengan nama <a href="' . route('manageuser.show', $user->username) . '"><strong>' . $user->fullname . '</strong></a> telah terdaftar. Silahkan periksa file curriculum vitae nya. Jika valid, ubah aktifnya menjadi Active dan statusnya menjadi Approved. Jika salah, ubah statusnya menjadi Rejected.'
                ]);
            }

            session(['new_user' => true], 2880);
            session(['new_user_id' => $user->id], 2880);
            session()->flash('success', 'Mohon menunggu 1x24 jam untuk proses verifikasi.');
            $this->redirectTo = route('verification_info');
        }

        if ($data['role'] === 'pembaca') {
            $user->is_active = true;
            $user->status = 'approved';
            $user->save();

            Profile::create([
                'users_id'   => $user->id
            ]);

            Notification::create([
                'notifiable_id' => $user->id,
                'notifiable_type' => 'App\Models\User',
                'title' => 'Selamat Bergabung di RuangLiterasi!',
                'message' => 'Halo <strong>' . $user->fullname . '</strong>, Selamat datang di platform kami! Kami ingin memberi tahu Anda bahwa kami sangat menghargai kehadiran Anda di sini. Jangan ragu untuk menjelajahi fitur-fitur yang tersedia dan jangan sungkan untuk menghubungi kami jika ada yang bisa kami bantu, terima kasih.<br><br> Tim RuangLiterasi'
            ]);

            session()->flash('success', 'Silahkan masuk untuk melanjutkan.');
        }

        return $user;
    }

    protected function storeFile($file, $prefix, $subDirectory)
    {
        $fileName = $prefix . str_replace([' ', '-', ':'], '', Carbon::now('Asia/Jakarta')->format('d-m-Y H:i:s')) . '-' . sha1(mt_rand(1, 999999) . microtime()) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs($subDirectory, $fileName, 'public');
        return 'storage/' . $filePath;
    }
}

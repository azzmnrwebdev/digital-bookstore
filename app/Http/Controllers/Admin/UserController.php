<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Ebook;
use App\Models\Author;
use App\Models\Profile;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');
        $query  = User::with('profile', 'author');

        if (!empty($search)) {
            $query->where('fullname', 'LIKE', '%' . $search . '%')
                ->orWhere('username', 'LIKE', '%' . $search . '%')
                ->orWhere('email', 'LIKE', '%' . $search . '%');
        }

        $users = $query->orderByRaw("FIELD(role, 'admin', 'penulis', 'pembaca') ASC")->orderBy('id', 'DESC')->paginate(10);
        return view('admin.page.manageuser.index', compact('users', 'search'));
    }

    public function create()
    {
        return view('admin.page.manageuser.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname'     => 'required|string|min:5|max:50',
            'username'     => 'required|string|min:5|max:50|unique:users,username',
            'email'        => 'required|string|email|max:50|unique:users,email',
            'phone_number' => 'nullable|numeric|digits_between:10,13|regex:/^([0-9\s\-\+\(\)]*)$/|unique:users,phone_number',
            'gender'       => 'nullable',
            'tgl_lahir'    => 'nullable|date',
            'role'         => 'required',
            'password'     => 'required|string|min:8',
            'avatar'       => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'background'   => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($request->has('role') && $request->input('role') === 'penulis') {
            $validator->sometimes('cv', 'required|file|mimes:pdf|max:5120', function ($input) {
                return $input->role === 'penulis';
            });
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userCount = User::where('role', 'admin')->count();
        if ($request->input('role') === 'admin' && $userCount >= 2) {
            return redirect()->back()->withErrors(['role' => 'Maximum number of admin users reached.']);
        }

        $user = User::create([
            'fullname'     => $request->input('fullname'),
            'username'     => strtolower(str_replace(' ', '', $request->input('username'))),
            'email'        => strtolower(str_replace(' ', '', $request->input('email'))),
            'gender'       => $request->input('gender'),
            'tgl_lahir'    => $request->input('tgl_lahir'),
            'phone_number' => $request->input('phone_number'),
            'role'         => $request->input('role'),
            'password'     => Hash::make($request->input('password')),
        ]);

        if ($request->input('role') === 'admin') {
            $this->storeAdmin($user, $request);
        } else if ($request->input('role') === 'penulis') {
            $this->storeAuthor($user, $request);

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
        } else if ($request->input('role') === 'pembaca') {
            $this->storeProfile($user, $request);

            Notification::create([
                'notifiable_id' => $user->id,
                'notifiable_type' => 'App\Models\User',
                'title' => 'Selamat Bergabung di RuangLiterasi!',
                'message' => 'Halo <strong>' . $user->fullname . '</strong>, Selamat datang di platform kami! Kami ingin memberi tahu Anda bahwa kami sangat menghargai kehadiran Anda di sini. Jangan ragu untuk menjelajahi fitur-fitur yang tersedia dan jangan sungkan untuk menghubungi kami jika ada yang bisa kami bantu, terima kasih.<br><br> Tim RuangLiterasi'
            ]);
        }

        return redirect(route('manageuser.index'))->with('success', 'Data pengguna berhasil disimpan.');
    }

    public function uploadFile($file, $prefix, $subDirectory)
    {
        if (!$file) return null;

        $fileName = $prefix . str_replace([' ', '-', ':'], '', Carbon::now('Asia/Jakarta')->format('d-m-Y H:i:s')) . '-' . sha1(mt_rand(1, 999999) . microtime()) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs($subDirectory, $fileName, 'public');
        return 'storage/' . $filePath;
    }

    private function storeAdmin(User $user, Request $request)
    {
        $user->is_active = true;
        $user->status = 'approved';
        $user->save();

        $avatar = $this->uploadFile($request->file('avatar'), 'admin-' . $user->username, 'avatar/admin');
        $background = $this->uploadFile($request->file('background'), 'admin-' . $user->username, 'background/admin');

        Profile::create([
            'users_id'   => $user->id,
            'avatar'     => $avatar,
            'background' => $background,
        ]);
    }

    private function storeProfile(User $user, Request $request)
    {
        $user->is_active = true;
        $user->status = 'approved';
        $user->save();

        $avatar = $this->uploadFile($request->file('avatar'), 'pembaca-' . $user->username, 'avatar/pembaca');
        $background = $this->uploadFile($request->file('background'), 'pembaca-' . $user->username, 'background/pembaca');

        Profile::create([
            'users_id'   => $user->id,
            'avatar'     => $avatar,
            'background' => $background,
        ]);
    }

    private function storeAuthor(User $user, Request $request)
    {
        $user->role = 'penulis';
        $user->save();

        $avatar = $this->uploadFile($request->file('avatar'), 'penulis-' . $user->username, 'avatar/penulis');
        $background = $this->uploadFile($request->file('background'), 'penulis-' . $user->username, 'background/penulis');
        $curriculumVitae = $this->uploadFile($request->file('cv'), 'penulis-' . $user->username, 'cv');

        Author::create([
            'users_id'   => $user->id,
            'bio'        => $request->input('bio'),
            'avatar'     => $avatar,
            'background' => $background,
            'cv'         => $curriculumVitae,
        ]);
    }

    public function show($username)
    {
        $manageuser = User::where('username', $username)->firstOrFail();
        $model = ($manageuser->role === 'penulis') ? Author::where('users_id', $manageuser->id)->first() : Profile::where('users_id', $manageuser->id)->first();
        return view('admin.page.manageuser.show', compact('manageuser', 'model'));
    }

    public function edit($username)
    {
        $manageuser = User::where('username', $username)->firstOrFail();
        $model = ($manageuser->role === 'penulis') ? Author::where('users_id', $manageuser->id)->first() : Profile::where('users_id', $manageuser->id)->first();
        return view('admin.page.manageuser.edit', compact('manageuser', 'model'));
    }

    public function update(Request $request, User $manageuser)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'fullname'      => 'required|string|min:5|max:50',
            'username'      => 'required|string|min:5|max:50|unique:users,username,' . $manageuser->id,
            'email'         => 'required|string|email|max:50|unique:users,email,' . $manageuser->id,
            'phone_number'  => 'nullable|numeric|digits_between:10,13|regex:/^([0-9\s\-\+\(\)]*)$/|unique:users,phone_number,' . $manageuser->id,
            'gender'        => 'nullable',
            'tgl_lahir'     => 'nullable|date',
            'old_password'  => 'nullable|required_with:new_password|string',
            'new_password'  => 'nullable|required_with:old_password|string|min:8',
            'avatar'        => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'background'    => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $data['username'] = strtolower(str_replace(' ', '', $data['username']));
        $data['email'] = strtolower(str_replace(' ', '', $data['email']));

        if ($manageuser->role === 'penulis') {
            $validator->sometimes('cv', 'required|file|mimes:pdf|max:5120', function ($input) use ($manageuser) {
                return $input->role === 'penulis' && (!$manageuser->author || $input->cv !== null || $manageuser->author->cv === null);
            });
        } else {
            $validator->sometimes('cv', 'file|mimes:pdf|max:5120', function ($input) use ($manageuser) {
                return $input->role === 'penulis' && ($input->cv !== null || $manageuser->author->cv === null);
            });
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $process = $request->has('status') ? $data['status'] === 'process' : '';
        $approved = $request->has('status') ? $data['status'] === 'approved' : '';
        $rejected = $request->has('status') ? $data['status'] === 'rejected' : '';

        if ($manageuser->status === 'approved' && $process) {
            return redirect()->back()->withErrors(['status' => "User account status cannot be changed"]);
        }

        if ($manageuser->status === 'approved' && $rejected) {
            return redirect()->back()->withErrors(['status' => "User account status cannot be changed"]);
        }

        if ($manageuser->status === 'rejected' && $process) {
            return redirect()->back()->withErrors(['status' => "User account status cannot be changed"]);
        }

        if ($manageuser->status === 'rejected' && $approved) {
            return redirect()->back()->withErrors(['status' => "User account status cannot be changed"]);
        }

        if ($request->filled('old_password') && $request->filled('new_password')) {
            if (!Hash::check($data['old_password'], $manageuser->password)) {
                return redirect()->back()->withErrors(['old_password' => "The old password doesn't match"]);
            }

            $manageuser->password = Hash::make($data['new_password']);
        }

        if ($manageuser->role === 'admin') {
            $profile = $manageuser->profile;

            if ($request->has('avatar')) {
                $data['avatar']  = $this->updateFile($request->file('avatar'), $profile->avatar, 'admin-' . $manageuser->username, 'avatar/admin');
                $profile->avatar = $data['avatar'];
            } else unset($data['avatar']);

            if ($request->has('background')) {
                $data['background']  = $this->updateFile($request->file('background'), $profile->background, 'admin-' . $manageuser->username, 'background/admin');
                $profile->background = $data['background'];
            } else unset($data['background']);

            $profile->save();
        } else if ($manageuser->role === 'penulis') {
            $author = $manageuser->author;
            $author->bio = $data['bio'];

            if ($request->has('avatar')) {
                $data['avatar']  = $this->updateFile($request->file('avatar'), $author->avatar, 'penulis-' . $manageuser->username, 'avatar/penulis');
                $author->avatar = $data['avatar'];
            } else unset($data['avatar']);

            if ($request->has('background')) {
                $data['background']  = $this->updateFile($request->file('background'), $author->background, 'penulis-' . $manageuser->username, 'background/penulis');
                $author->background = $data['background'];
            } else unset($data['background']);

            if ($request->has('cv')) {
                $data['cv']  = $this->updateFile($request->file('cv'), $author->cv, 'penulis-' . $manageuser->username, 'cv');
                $author->cv = $data['cv'];
            } else unset($data['cv']);

            $author->save();
        } else if ($manageuser->role === 'pembaca') {
            $profile = $manageuser->profile;

            if ($request->has('avatar')) {
                $data['avatar']  = $this->updateFile($request->file('avatar'), $profile->avatar, 'pembaca-' . $manageuser->username, 'avatar/pembaca');
                $profile->avatar = $data['avatar'];
            } else unset($data['avatar']);

            if ($request->has('background')) {
                $data['background']  = $this->updateFile($request->file('background'), $profile->background, 'pembaca-' . $manageuser->username, 'background/pembaca');
                $profile->background = $data['background'];
            } else unset($data['background']);

            $profile->save();
        }

        $manageuser->update($data);
        return redirect(route('manageuser.index'))->with('success', 'Data pengguna berhasil diperbarui.');
    }

    private function updateFile($file, $oldFile, $prefix, $subDirectory)
    {
        if (!$file) return null;

        if (!empty($oldFile) && Storage::disk('public')->exists(Str::after($oldFile, 'storage/'))) {
            Storage::disk('public')->delete(Str::after($oldFile, 'storage/'));
        }

        $fileName = $prefix . str_replace([' ', '-', ':'], '', Carbon::now('Asia/Jakarta')->format('d-m-Y H:i:s')) . '-' . sha1(mt_rand(1, 999999) . microtime()) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs($subDirectory, $fileName, 'public');
        return 'storage/' . $filePath;
    }

    public function destroy(User $manageuser)
    {
        if ($manageuser->role !== 'admin') {
            if (isset($manageuser->author) && $manageuser->role === 'penulis') {
                $author = $manageuser->author;
                $ebookIds = $author->ebooks()->pluck('id')->toArray();

                foreach ($ebookIds as $ebookId) {
                    $ebook = Ebook::find($ebookId);
                    if ($ebook && $ebook->authors->contains($author->id)) {
                        $this->deleteFileIfExists($ebook->pdf);
                        $this->deleteFileIfExists($ebook->thumbnail);
                        $ebook->forceDelete();
                    }
                }

                $this->deleteFileIfExists($manageuser->author->avatar);
                $this->deleteFileIfExists($manageuser->author->background);
                $this->deleteFileIfExists($manageuser->author->cv);
            } elseif (isset($manageuser->profile)) {
                $this->deleteFileIfExists($manageuser->profile->avatar);
                $this->deleteFileIfExists($manageuser->profile->background);
            }

            $manageuser->delete();
            return redirect(route('manageuser.index'))->with('success', 'Data pengguna berhasil dihapus.');
        }

        return redirect(route('manageuser.index'))->with('error', 'Anda tidak dapat menghapus akun admin sendiri.');
    }

    private function deleteFileIfExists($file)
    {
        if (!empty($file) && Storage::disk('public')->exists(Str::after($file, 'storage/'))) {
            Storage::disk('public')->delete(Str::after($file, 'storage/'));
        }
    }

    public function manageuserPDF()
    {
        $manageuser = User::all();
        $keterangan = [
            'title' => 'Data Pengguna',
            'date' => date('m/d/Y'),
        ];

        $pdf = Pdf::loadView('admin.page.manageuser.userPDF', ['manageuser' => $manageuser], $keterangan);
        return $pdf->download('datapengguna.pdf');
    }
}

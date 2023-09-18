<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('admin.page.setting.change-password');
    }

    public function updatePassword(Request $request)
    {
        $userLogin = Auth::user();

        $validated =  Validator::make($request->all(), [
            'current_password' => 'required|required_with:new_password|string',
            'new_password'  => 'required|required_with:current_password|string|min:8|confirmed',
        ]);

        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (!Hash::check($request->input('current_password'), $userLogin->password)) {
                return redirect()->back()->withErrors(['current_password' => "The current password doesn't match"]);
            }

            $userLogin->password = Hash::make($request->input('new_password'));
        }

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        $userLogin->save();
        return redirect()->back()->with('success', 'Password changed successfully!');
    }

    public function showUpdateProfileForm()
    {
        $userLogin = Auth::user();
        $admin = Profile::where('users_id', $userLogin->id)->first();
        return view('admin.page.setting.update-profile', compact('userLogin', 'admin'));
    }

    public function updateProfile(Request $request, $id)
    {
        $data = $request->all();
        $user = User::findOrFail($id);

        $validator = Validator::make($data, [
            'fullname'      => 'required|string|max:50',
            'username'      => 'required|string|max:50|unique:users,username,' . $id,
            'email'         => 'required|string|email|max:50|unique:users,email,' . $id,
            'phone_number'  => 'nullable|numeric|digits_between:10,13|regex:/^([0-9\s\-\+\(\)]*)$/|unique:users,phone_number,' . $id,
            'gender'        => 'nullable',
            'tgl_lahir'     => 'nullable|date',
            'avatar'        => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'background'    => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $admin = $user->profile;

        if ($request->has('avatar')) {
            $data['avatar']  = $this->updateFile($request->file('avatar'), $admin->avatar, 'penulis-' . $user->username, 'avatar/penulis');
            $admin->avatar = $data['avatar'];
        } else unset($data['avatar']);

        if ($request->has('background')) {
            $data['background']  = $this->updateFile($request->file('background'), $admin->background, 'penulis-' . $user->username, 'background/penulis');
            $admin->background = $data['background'];
        } else unset($data['background']);

        $admin->save();
        $user->update($data);

        return redirect(route('admin.profile'))->with('success', 'User data updated successfully!');
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
}

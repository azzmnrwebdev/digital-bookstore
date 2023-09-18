<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Ebook;
use App\Models\Profile;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function profilPembaca()
    {
        $user = Auth::user();
        $pembaca = Profile::with('user')->where('users_id', $user->id)->first();
        return view('home.page.pembaca-profile', compact('pembaca'));
    }

    public function showChangePasswordForm()
    {
        return view('home.page.pembaca-change-password');
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
        return redirect(route('pembaca.profile'))->with('success', 'Password changed successfully!');
    }

    public function editProfile($username)
    {
        $user = Auth::user();
        $pembaca = Profile::with('user')->whereHas('user', function ($query) use ($username) {
            $query->where('username', $username);
        })->where('users_id', $user->id)->first();

        return view('home.page.pembaca-edit-profile', compact('pembaca'));
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

        $pembaca = $user->profile;

        if ($request->has('avatar')) {
            $data['avatar']  = $this->updateFile($request->file('avatar'), $pembaca->avatar, 'penulis-' . $user->username, 'avatar/penulis');
            $pembaca->avatar = $data['avatar'];
        } else unset($data['avatar']);

        if ($request->has('background')) {
            $data['background']  = $this->updateFile($request->file('background'), $pembaca->background, 'penulis-' . $user->username, 'background/penulis');
            $pembaca->background = $data['background'];
        } else unset($data['background']);

        $pembaca->save();
        $user->update($data);

        return redirect(route('pembaca.profile'))->with('success', 'Data changed successfully!');
    }

    public function author($username)
    {
        $author = Author::with('user')->whereHas('user', function ($query) use ($username) {
            $query->where('username', $username);
        })->firstOrFail();

        $ebooks = Ebook::with('categories', 'authors')->whereHas('authors', function ($query) use ($author) {
            $query->where('authors_id', $author->id);
        })->get();

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

        return view('home.page.profil-penulis', compact('author', 'ebooks', 'ebookCounts', 'averageRatings'));
    }
}

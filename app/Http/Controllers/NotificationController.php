<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = Notification::where(function ($query) use ($user) {
            $query->whereHasMorph('notifiable', [User::class, Author::class], function ($innerQuery, $type) use ($user) {
                if ($type === User::class) {
                    $innerQuery->where('id', $user->id);
                }

                if ($type === Author::class) {
                    $innerQuery->where('users_id', $user->id);
                }
            });
        })->orderByDesc('updated_at')->get();

        $unreadNotifications = $notifications->where('read', false);
        $readNotifications = $notifications->where('read', true);
        return view('home.page.notification', compact('unreadNotifications', 'readNotifications'));
    }

    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        if (!$notification->read) {
            $notification->read = true;
            $notification->save();
        }

        return view('home.page.detail-notification', compact('notification'));
    }

    public function unread()
    {
        $user = Auth::user();
        $unreadNotifications = Notification::where(function ($query) use ($user) {
            $query->whereHasMorph('notifiable', [User::class, Author::class], function ($innerQuery, $type) use ($user) {
                if ($type === User::class) {
                    $innerQuery->where('id', $user->id);
                }

                if ($type === Author::class) {
                    $innerQuery->where('users_id', $user->id);
                }
            });
        })->where('read', false)->orderByDesc('created_at')->get();

        return response()->json(['count' => $unreadNotifications->count()], 200);
    }

    public function destroy($id)
    {
        $notification = Notification::find($id);
        $notification->delete();
        return redirect(route('notification.index'))->with('success', 'Notifikasi berhasil dihapus.');
    }
}

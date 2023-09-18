<?php

namespace App\Http\Controllers\Penulis;

use App\Models\Author;
use App\Models\Notification;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $penulisNotification = Notification::where(function ($query) use ($user) {
            $query->whereHasMorph('notifiable', [User::class, Author::class], function ($innerQuery, $type) use ($user) {
                if ($type === User::class) {
                    $innerQuery->where('id', $user->id);
                }

                if ($type === Author::class) {
                    $innerQuery->where('users_id', $user->id);
                }
            });
        })->orderByDesc('updated_at')->get();

        $unreadNotifications = $penulisNotification->where('read', false);
        $readNotifications = $penulisNotification->where('read', true);
        return view('penulis.page.notification.index', compact('unreadNotifications', 'readNotifications'));
    }

    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        if (!$notification->read) {
            $notification->read = true;
            $notification->save();
        }

        return view('penulis.page.notification.show', compact('notification'));
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
        return redirect(route('penulis.notification.index'))->with('success', 'Notifikasi berhasil dihapus.');
    }
}

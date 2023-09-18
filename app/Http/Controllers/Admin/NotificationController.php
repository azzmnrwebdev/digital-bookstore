<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $adminNotifications = Notification::where(function ($query) use ($user) {
            $query->whereHasMorph('notifiable', [User::class], function ($innerQuery, $type) use ($user) {
                if ($type === User::class) {
                    $innerQuery->where('id', $user->id);
                }
            });
        })->orderByDesc('updated_at')->get();

        $unreadNotifications = $adminNotifications->where('read', false);
        $readNotifications = $adminNotifications->where('read', true);
        return view('admin.page.notification.index', compact('unreadNotifications', 'readNotifications'));
    }

    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        if (!$notification->read) {
            $notification->read = true;
            $notification->save();
        }

        return view('admin.page.notification.show', compact('notification'));
    }

    public function unread()
    {
        $user = Auth::user();
        $unreadNotifications = Notification::where(function ($query) use ($user) {
            $query->whereHasMorph('notifiable', [User::class], function ($innerQuery, $type) use ($user) {
                if ($type === User::class) {
                    $innerQuery->where('id', $user->id);
                }
            });
        })->where('read', false)->orderByDesc('created_at')->get();

        return response()->json(['count' => $unreadNotifications->count()], 200);
    }

    public function destroy($id)
    {
        $notification = Notification::find($id);
        $notification->delete();
        return redirect(route('admin.notification.index'))->with('success', 'Notifikasi berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationsCollection;
use App\NotifCount;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Show notification view
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $title = 'Notifications';

        return view('notifications.index', compact('title'));
    }

    /**
     * Get notifications collection
     *
     * @param   Request  $request  
     *
     * @return  NotificationsCollection             
     */
    public function getNotifications(Request $request)
    {
        $auth = Auth::user();
        $query = $auth->notifications();

        if ($request->has('tab')) {
            $tab = $request->get('tab');

            switch (strtolower($tab)) {
                case 'read':
                    $query->whereNotNull('read_at');
                    break;

                case 'unread':
                    $query->whereNull('read_at');
                    break;
            }
        }

        // Get the notifications collection
        $notifications = $query->paginate(10);

        // Extract the emitters
        $userIds = collect($notifications->items())->pluck('data.from_user_id');
        $users = User::select('id', 'avatar', 'username')->whereIn('id', $userIds)->get();

        return (new NotificationsCollection($notifications, $users))
            ->additional([
                'ok' => true
            ]);
    }

    /**
     * Get user notification counts
     *
     * @return  \Illuminate\Http\Response
     */
    public function count()
    {
        $auth = auth()->user();
        $notif = NotifCount::where('user_id', $auth->id)->first();

        return response()->json([
            'ok' => true,
            'data' => [
                'count' => $notif->count,
            ],
        ]);
    }

    /**
     * Reset user notification count
     *
     * @return  \Illuminate\Http\Response
     */
    public function countReset()
    {
        NotifCount::where('user_id', Auth::id())
            ->update([
                'count' => 0,
            ]);

        return response()->json([
            'ok' => true,
            'message' => 'Success to reset notification count.',
        ], 202);
    }

    /**
     * Mark notification as read
     *
     * @param   int  $id  
     *
     * @return  \Illuminate\Http\Response
     */
    public function markRead(int $id)
    {
        $auth = Auth::user();
        $notif = $auth->notifications()->where('id', $id)->firstOrFail();

        $notif->update([
            'read_at' => Carbon::now()
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Success to mark notification.'
        ]);
    }

    /**
     * Unmark notification as read
     *
     * @param   int  $id  
     *
     * @return  \Illuminate\Http\Response
     */
    public function unMarkRead(int $id)
    {
        $auth = Auth::user();
        $notif = $auth->notifications()->where('id', $id)->firstOrFail();

        $notif->update([
            'read_at' => null,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Success to mark notification.'
        ]);
    }

    /**
     * Delete notification
     *
     * @param   string  $id  
     *
     * @return  \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $auth = Auth::user();
        $notif = $auth->notifications()->where('id', $id)->firstOrFail();

        // Delete
        $notif->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Success to delete notification.'
        ], 202);
    }
}

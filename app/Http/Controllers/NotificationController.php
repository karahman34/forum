<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationsCollection;
use App\NotifCount;
use App\User;
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
}

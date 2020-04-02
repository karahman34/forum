<?php

namespace App\Http\Controllers;

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
     * Get user notification counts
     *
     * @return  \Illuminate\Http\Response
     */
    public function count()
    {
        $count = auth()->user()->notifications()->count();

        return response()->json([
            'ok' => true,
            'data' => [
                'count' => $count
            ],
        ]);
    }
}

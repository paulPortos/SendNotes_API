<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifications;


class NotificationsController extends Controller
{
    public function shownotif()
    {
        return Notifications::all();
    }

    public function makenotif(Request $request)
    {
        $fields = $request->validate([
            'notification_type' => 'required|string|max:50',
            'email' => 'required|email|string',
            'message' => 'required|string'
        ]);

        $notif = Notifications::create($fields);

        return response()->json($notif, 201);
    }

}

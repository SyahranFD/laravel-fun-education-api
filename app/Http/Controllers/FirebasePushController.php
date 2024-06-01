<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebasePushController extends Controller
{
    protected $notification;
    public function __construct()
    {
        $this->notification = Firebase::messaging();
    }

    public function setToken(Request $request)
    {
        $token = $request->input('fcm_token');
        $request->user()->update([
            'fcm_token' => $token
        ]);
        return response()->json([
            'message' => 'Successfully Updated FCM Token'
        ]);
    }

    public function notification(Request $request)
    {
        $FcmToken = auth()->user()->fcm_token;
        $title = $request->input('title');
        $body = $request->input('body');
        $message = CloudMessage::fromArray([
            'token' => $FcmToken,
            'notification' => [
                'title' => $title,
                'body' => $body
            ],
        ]);

        $this->notification->send($message);
    }
}

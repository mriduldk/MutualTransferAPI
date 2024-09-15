<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class FCMService
{
    /**
     * Send notification to a Firebase topic
     *
     * @param string $title
     * @param string $body
     * @param string $topic
     * @return array
     */
    public function sendNotificationToTopic($title, $body, $topic)
    {
        $firebase = (new Factory)->withServiceAccount(__DIR__.'/../../storage/mutual-transfer-ce4c3-7aa7fc4ba00b.json');
 
        $messaging = $firebase->createMessaging();
 
        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification(['title' => $title, 'body' => $body ]);
 
        $messaging->send($message);
 
        return response()->json(['message' => 'Push notification sent successfully']);
    }


    public function sendNotificationToToken($title, $body, $token)
    {
        $firebase = (new Factory)->withServiceAccount(__DIR__.'/../../storage/mutual-transfer-ce4c3-7aa7fc4ba00b.json');
 
        $messaging = $firebase->createMessaging();
 
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(['title' => $title, 'body' => $body ]);
 
        $messaging->send($message);
 
        return response()->json(['message' => 'Push notification sent successfully']);
    }
}

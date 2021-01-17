<?php

return [
    'driver' => env('FCM_PROTOCOL', 'http'),
    'log_enabled' => false,

    'http' => [
        'server_key' => env('FCM_SERVER_KEY', 'AAAALPlme-Q:APA91bGHlHiD46vRfH9Jbd03vUwcEwkLJ_TK8JjRNw35ZoPCeq3gZNDcOThdiUYslAssFG5evQMpDm2a730YhVCXv_mCswhwgiK8m1xPQ8pj5FzLtY2-YOtWziK9cuPhyLbcm4OGhpXo'),
        'sender_id' => env('FCM_SENDER_ID', '193162804196'),
        'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
        'server_group_url' => 'https://android.googleapis.com/gcm/notification',
        'timeout' => 10.0, // in second
    ],
];

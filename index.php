<?php
$access_token = 'EAAB6iHUDdz8BAAZAGXuPahCBUw3yk8ZBvCiO2YZC7eZA8O3ZByJjS93fJkRKdYekZAbdeUbwZBkf6UirtyLque0aRhs6GDlZCdGhy9qYDdsznzV61Mp2hy0HLZCwMmXUwKzSManFoT7k9dZBKe6JLbZC7Mae8i78qlRgiiBrqIql6ZCZBPgZDZD';

/* validate verify token needed for setting up web hook */ 

if (isset($_GET['hub_verify_token'])) { 
    if ($_GET['hub_verify_token'] === $access_token) {
        echo $_GET['hub_challenge'];
        return;
    } else {
        echo 'Invalid Verify Token';
        return;
    }
}

/* receive and send messages */
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['entry'][0]['messaging'][0]['sender']['id'])) {

    $sender = $input['entry'][0]['messaging'][0]['sender']['id']; //sender facebook id 

    $url = 'https://graph.facebook.com/v2.6/me/messages?access_token='. $access_token;

    /*initialize curl*/
    $ch = curl_init($url);

    /*prepare response*/
    $resp     = array(
        'messaging_type' => 'RESPONSE',
        'recipient' => array(
            'id' => $sender
        ),
        'message' => array(
            'attachment' => array(
                'type' => 'video',
                'payload' => array(
                    'url' => 'https://gcs-vimeo.akamaized.net/exp=1511793265~acl=%2A%2F796537681.mp4%2A~hmac=5f7c7efdc7e2b590d99d7aecd007628a4a8bb7d718d449b2126fa43d02e20a73/vimeo-prod-skyfire-std-us/01/324/9/226624975/796537681.mp4',
                    'is_reusable' => true
                )
            )
        )
    );
    $jsonData = json_encode($resp);

    /* curl setting to send a json post data */
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    $result = curl_exec($ch); // user will get the message
}
?>

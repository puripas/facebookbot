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
    $resp = array(
      'messaging_type' => 'RESPONSE',  
      'recipient' => array(
        'id' => $sender
      ),
      'message' => array(
        'attachment' => array(
            'type' => 'image',
            'payload' => array(
                'url' => 'https://images.unsplash.com/photo-1490312278390-ab64016e0aa9?auto=format&fit=crop&w=1500&q=60&ixid=dW5zcGxhc2guY29tOzs7Ozs%3D',
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

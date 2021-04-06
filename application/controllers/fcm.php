<?php

class FCM extends CI_Controller {

	static public function sendPushNotification($title, $body, $data, $token) {
	    $url = "https://fcm.googleapis.com/fcm/send";
	    $serverKey = 'AAAA5ZpLvVo:APA91bF3ebfiy61PEdoGnQ5buQtcNWoXtI4b6_Xos4aiTzlODsScXvyYxpOz7BdaV7yC1qf69Ig1XmSlyMxtbASwtPi70byr4phe6NDuMiFSBr62aIij0kPqCY_foIzsH-E50qgNYkCb';
	    $notification = array('title' => $title, 'body' => $body, 'sound' => 'default', 'badge' => '1');
	    $data['title'] = $title;
	    $data['body'] = $body;
	    $arrayToSend = array('to' => $token, 'notification' => $notification, 'priority'=>'high', 'data' => $data);
	    $json = json_encode($arrayToSend);
	    $headers = array();
	    $headers[] = 'Content-Type: application/json';
	    $headers[] = 'Authorization: key='. $serverKey;
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,TRUE);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
	    //Send the request
	    $response = curl_exec($ch);
	    //Close request
	    if ($response === FALSE) {
	    	die('FCM Send Error: ' . curl_error($ch));
	    }
	    curl_close($ch);
	    echo $response;
	}
	
	static public function send_message($token, $notificationType, $showNotification, $title, $body, $data) {
	  $data['show_notification'] = $showNotification;
	  $data['notification_type'] = $notificationType;
      FCM::sendPushNotification($title, $body, $data, $token);
    }

	static public function sendPushNotificationWithColor($title, $body, $color, $data, $token) {
	    $url = "https://fcm.googleapis.com/fcm/send";
	    $serverKey = 'AAAA5ZpLvVo:APA91bF3ebfiy61PEdoGnQ5buQtcNWoXtI4b6_Xos4aiTzlODsScXvyYxpOz7BdaV7yC1qf69Ig1XmSlyMxtbASwtPi70byr4phe6NDuMiFSBr62aIij0kPqCY_foIzsH-E50qgNYkCb';
	    $notification = array('title' => $title, 'body' => $body, 'color' => $color, 'sound' => 'default', 'badge' => '1');
	    $arrayToSend = array('to' => $token, 'notification' => $notification, 'priority'=>'high', 'data' => $data);
	    $json = json_encode($arrayToSend);
	    $headers = array();
	    $headers[] = 'Content-Type: application/json';
	    $headers[] = 'Authorization: key='. $serverKey;
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,TRUE);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
	    //Send the request
	    $response = curl_exec($ch);
	    //Close request
	    if ($response === FALSE) {
	    	die('FCM Send Error: ' . curl_error($ch));
	    }
	    curl_close($ch);
	}
	
	static public function send_message_with_color($token, $notificationType, $showNotification, $title, $body, $color, $data) {
	  $data['show_notification'] = $showNotification;
	  $data['notification_type'] = $notificationType;
      FCM::sendPushNotificationWithColor($title, $body, $color, $data, $token);
    }

	static public function sendPushNotificationWithoutNotification($data, $token) {
	    $url = "https://fcm.googleapis.com/fcm/send";
	    $serverKey = 'AAAA5ZpLvVo:APA91bF3ebfiy61PEdoGnQ5buQtcNWoXtI4b6_Xos4aiTzlODsScXvyYxpOz7BdaV7yC1qf69Ig1XmSlyMxtbASwtPi70byr4phe6NDuMiFSBr62aIij0kPqCY_foIzsH-E50qgNYkCb';
	    $arrayToSend = array('to' => $token, 'priority'=>'high', 'data' => $data);
	    $json = json_encode($arrayToSend);
	    $headers = array();
	    $headers[] = 'Content-Type: application/json';
	    $headers[] = 'Authorization: key='. $serverKey;
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,TRUE);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
	    //Send the request
	    $response = curl_exec($ch);
	    //Close request
	    if ($response === FALSE) {
	    	die('FCM Send Error: ' . curl_error($ch));
	    }
	    curl_close($ch);
	}
	
	static public function send_message_without_notification($token, $notificationType, $data) {
	  $data['show_notification'] = 0;
	  $data['notification_type'] = $notificationType;
      FCM::sendPushNotificationWithoutNotification($data, $token);
    }
}

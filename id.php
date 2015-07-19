<?php
$text = $_GET['text'];
$token = $_GET['token'];
$channel = $_GET['channel_id'];
$user = $_GET["user_name"];
#echo $_GET['channel_name'];
$link = "http://help.quo.cc/helpdesk/tickets/$text";
$url = "https://hooks.slack.com/services/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";


$fields = array(
						'channel' => $channel,
						'username' => $user,
						'text' => $link ,
						'icon_url' => "https://assets.getapp.com/logo/r/91351-79785391.png"
				);
				
		
$fields_string = json_encode($fields);
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
curl_exec($ch);

//close connection
curl_close($ch);
?>
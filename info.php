<?php
namespace MyApplication;
use Freshservice as FS;
include_once ("freshservice-api/Freshservice.class.php");
include_once ("freshservice-api/FreshserviceException.class.php");
include_once ("freshservice-api/LoginCredentials.class.php");
include_once ("freshservice-api/RestCommands.class.php");






$username = "riley@quo.cc";
$password = "zYLzPDjsoetzPwPyVCKKvVwyJkUnBa";
	$text = $_GET['text'];
	$token = $_GET['token'];
	$channel = $_GET['channel_id'];
	$user = $_GET["user_name"];
	
	#echo $_GET['channel_name'];
	
	$url = "https://hooks.slack.com/services/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";
	
	if (!is_numeric($text))
	die("That is not a ticket number");
	
	$lc = FS\LoginCredentials::authenticateWithUserCredentials($username, $password);
	$fs = new FS\Freshservice("http://help.quo.cc", $lc);
	// get all tickets
	$response = $fs->Exec("/helpdesk/tickets/$text.json", FS\RestCommands::GET);
	if (isset($response["errors"]))
		die("Invalid Command");
	
	$info = array (
  'attachments' => 
  array (
  
      'fallback' => 'ReferenceError - UI is not definied: https://honeybadger.io/path/to/event/',
      'text' => 'Ticket <http://help.quo.cc/helpdesk/tickets/'. $text . '>'. $text .'',
      'fields' => 
      array (
        0 => 
        array (
          'title' => 'Subject',
          'value' => $response["helpdesk_ticket"]["subject"],
          'short' => true,
        ),
        1 => 
        array (
          'title' => 'Requester',
          'value' => $response["helpdesk_ticket"]["requester_name"],
          'short' => true,
        ),
        2 => 
        array (
          'title' => 'Owner',
          'value' => $response["helpdesk_ticket"]["responder_name"],
          'short' => true,
        ),
        3 => 
        array (
          'title' => 'Company',
          'value' => $response["helpdesk_ticket"]["department_name"],
          'short' => true,
        ),
      ),
      'color' => '#F35A00',
    ),
  
);
	
	
	
	
	$fields = array(
							'channel' => "#test-slack",
							'username' => "Ticket Info",
							'text' => $info ,
							'icon_url' => "http://www.highberryfestival.com/wp-content/uploads/2015/06/ticket.png"
					);
				
		
$fields_string = json_encode($fields);
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
$response = curl_exec($ch);

//close connection
curl_close($ch);
?>
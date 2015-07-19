<?php
namespace MyApplication;
use Freshservice as FS;
include_once ("freshservice-api/Freshservice.class.php");
include_once ("freshservice-api/FreshserviceException.class.php");
include_once ("freshservice-api/LoginCredentials.class.php");
include_once ("freshservice-api/RestCommands.class.php");






        $text = $_GET['text'];
        $token = $_GET['token'];
        $channel = $_GET['channel_id'];
        $user = $_GET["user_name"];

        #echo $_GET['channel_name'];

        $url = "https://hooks.slack.com/services/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";

        if (!is_numeric($text))
        die("That is not a ticket number");

        $lc = FS\LoginCredentials::authenticateWithToken("DKXAIL6wsw0oHxSlt4le");
        $fs = new FS\Freshservice("http://help.quo.cc", $lc);
        // get all tickets
        $response = $fs->Exec("/helpdesk/tickets/$text.json", FS\RestCommands::GET);
        //$response = json_decode($response, true);
        if (isset($response["errors"]))
                die("Invalid Command");
		$pc = array (
			"Low" => "#bee6ef",
			"Medium" => "#00c5ff",
			"High" => "#eaab00",
			"Urgent" => "#ff400f"
			
			);
        $info =  array (

      'fallback' => 'ReferenceError - UI is not definied: https://honeybadger.io/path/to/event/',
      'text' => '<http://help.quo.cc/helpdesk/tickets/'. $text . '|Ticket #' . $text . '>',
      'fields' =>
      array (

        array (
          'title' => 'Subject',
          'value' => $response["helpdesk_ticket"]["subject"],
          'short' => true,
        ),

        array (
          'title' => 'Requester',
          'value' => $response["helpdesk_ticket"]["requester_name"],
          'short' => true,
        ),

        array (
          'title' => 'Owner',
          'value' => $response["helpdesk_ticket"]["responder_name"],
          'short' => true,
        ),

        array (
          'title' => 'Company',
          'value' => $response["helpdesk_ticket"]["department_name"],
          'short' => true,
        ),
         array (
          'title' => 'Priority',
          'value' => $response["helpdesk_ticket"]["priority_name"],
          'short' => true,
        ),
        array (
          'title' => 'Type',
          'value' => $response["helpdesk_ticket"]["ticket_type"],
          'short' => true,
        ),
         array (
          'title' => 'Status',
          'value' => $response["helpdesk_ticket"]["status_name"],
          'short' => true,
        ),
      ),
      'color' => $pc[$response["helpdesk_ticket"]["status_name"]],
    );
    
$status =  $response["helpdesk_ticket"]["status_name"];
echo $pc[$status];


        $fields = array(
                                                        'channel' => $channel,
                                                        'username' => "Ticket Info",
                                                        'text' => "",
                                                        'attachments' => array($info) ,
                                                        'icon_url' => "http://www.highberryfestival.com/wp-content/uploads/2015/06/ticket.png"
                                        );

/*echo "<pre>";
var_dump ($fields);
*/
$fields_string = json_encode($fields);


$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
$response = curl_exec($ch);
curl_close($ch);
?>
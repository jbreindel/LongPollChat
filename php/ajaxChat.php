<?php
/*********************************************************************
 *
 * FILE:				ajaxChat.php
 * AUTHOR:				Jake Breindel
 * DATE:				4-7-2014
 *
 * DESRIPTION:
 * 	Ajax chat controller.
 *
 **********************************************************************/

//Clean house
ini_set('display_errors','0'); //Disable error display
ini_set('display_startup_errors','0');

//TODO: disable direct access via the browser? i,e All request must have REFER?
if(!defined('INCLUDE_DIR'))	Http::response(500, 'Server configuration error');

// get the chat api
require_once INCLUDE_DIR.'/ajax.chat.php';

// make a new chat ajax controller
$chatAjax = new ChatAjaxAPI();

// IF we're creating a new chat
if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	// get the chat from the json
	$chat = Chat::ChatFromArray($_POST);

	// output a created chat
	$raw = $chatAjax->createChat($chat, $errors);
	
	// IF there are errors
	if(!empty($errors)){
		
		// error out
		Http::response(400, 'Bad Request');
		exit;
	}
	
	// reference now
	$endDate = new DateTime();
	// clone the end date
	$startDate = new DateTime;
	$startDate = clone $endDate;
	
	// make a decent window
	$startDate -> modify("-1 year");
	
	// encode the chat message
	$output = $chatAjax->getChats(15, $startDate, $endDate);
	
}
// ELSE IF we're getting chats
else {
	
	if($_GET['start'] && $_GET['end']){
		
		// get the start and end dates
		$start = DateTime::createFromFormat("Y-m-d H:i:s", $_GET['start']);
		$end = DateTime::createFromFormat("Y-m-d H:i:s", $_GET['end']);
		
		// get the chats between start and end
		$output = $chatAjax->getChats(15, $start, $end);
	}
	// ELSE get all chats
	else{
		
		// get all the chats
		$output = $chatAjax->getChats(15, null, null);
		
	}
	
}

print $output;
?>

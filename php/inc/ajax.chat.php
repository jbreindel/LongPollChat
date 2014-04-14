<?
/*********************************************************************
 *
 * FILE:				ajax.chat.php
 * AUTHOR:				Jake Breindel
 * DATE:				4-7-2014
 *
 * DESRIPTION:
 * 	Ajax chat controller.
 *
 **********************************************************************/

if (!defined('INCLUDE_DIR'))
	die('!');

include_once(INCLUDE_DIR.'class.chat.php');

class ChatAjaxAPI {

	/**
	 * @return	$json	JSON represnetation of a list of chats
	 */
	public function getChats($limit = 15, $start = null, $end = null) {
		return json_encode(Chat::getChats($limit, $start, $end));
	}

	/**
	 * @param	$chat	chat in json representation 
	 *
	 * @return	$bool	returns a boolean if a chat can be created
	 */
	public function createChat($chat, $errors){
		return $chat->save($errors);
	}

}
?>

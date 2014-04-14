<?
/*********************************************************************
 *
 * FILE:				class.chat.php
 * AUTHOR:				Jake Breindel
 * DATE:				4-7-2014
 *
 * DESRIPTION:
 * 	A simple chat object that represents a chat message.
 *
 **********************************************************************/

class Chat {

	/********************************* INSTANCE VARS *************************************/

	/**@var id number of the chat */
	public $chat_id;
	/**@var name of the chatter */
	public $name;
	/**@var content of the chat */
	public $content;
	/**@var time when the chat was created */
	public $created;
	
	/********************************* CONSTRUCTORS *************************************/
	
	/**
	 * @param	$chat_id		the ID of the chat to load
	 */
	public function Chat($chat_id = null){
		
		// IF an ID was passed in
		if($id != null){
		
			// zero the id
			$this -> chat_id = 0;
			// load the scheduled
			$this -> load($chat_id);
			
		}
		
		return $this;
	}
	
	/**
	 * @param 	$assocArray		associative array representation of a chat
	 *
	 */
	public static function ChatFromArray($assocArray){
		
		// make a new scheduled
		$chat = new Chat();
		// load the data from the array
		$chat->loadFromArray($assocArray);
		
		return $chat;
	}
	
	/******************************************* MEMBER METHODS *******************************************/
	
	/**
	 * @param 	$id		primary key id
	 *
	 * 	loads the chat
	 */
	private function load($id) {

		// IF we don't have an id
		if (!$id && !($id = $this->getChatId()))
			return false;

		$loadQuery = "
			SELECT 
				*
			FROM
				".CHAT_TABLE."
			WHERE
				chat_id=".db_input($id);
		
		// IF the query fails
		if (!($res = db_query($loadQuery)) || !db_num_rows($res))
			return false;
		
		// get an array representation
		$array = db_assoc_array($res);
		
		// get the associative array
		$this->loadFromArray($array[0]);

		return true;
	
	}
	
	/**
	 * @param	$assocArray		loads the chat from its corresponding associative array
	 */
	private function loadFromArray($array){
		
		// assign the instance variables
		$this->chat_id = $array['chat_id'];
		$this->name = $array['name'];
		$this->content = $array['content'];
		$this->created = $array['created'];
		
	}
	
	/**
	 * @param 	$vars		variables for saving the organization
	 * @param 	&$errors	error reporting
	 * 
	 * @return  boolean
	 *
	 * 	Saves a chat to the database
	 */
	public function save(&$errors) {
		
		// error checking
		if(!$this->name){ $errors['err'] = "Chat Name Required."; }
		if(!$this->content){ $errors['err'] = "Chat Content Required."; }
		
		$sql = "
				INSERT INTO
					".CHAT_TABLE."
				SET
					 name = ".db_input($this->name)."
					,content = ".db_input($this->content)."
					,created = NOW()
		";
		
		// IF we're able to execute the query
		if($res = db_query($sql) && db_affected_rows($res) == 1){
			
			// return the last inserted row
			return TRUE;
		}
		// ELSE there was an error
		else{
			$errors['err'] = 'Unable to create a chat.';
		}
		
		// error out
		return false;
	}
	
	/**
	 * @param 	$start		the start time for when chats should be gotten from
	 * @param 	$end		the end time for when chats should be gotten untill
	 * 
	 * @return	$assocArray	Associative array representation of the chats
	 */
	public static function getChats($limit = 50, $start = null, $end = null){
						
		// IF we don't have either of the dates				
		if($start == null || $end == null){
			
			// get all chats
			$query = "
				SELECT
					*
				FROM 
					".CHAT_TABLE."
				GROUP BY
					chat_id
				ORDER BY
					created DESC
				LIMIT ". $limit;
					
		}
		// ELSE we have a section to retrieve
		else{
			
			// get chats between a section
			$query = "
				SELECT
					*
				FROM 
					".CHAT_TABLE."
				WHERE
					created
				BETWEEN
					'".$start->format('Y-m-d H:i:s')."'
				AND
					'".$end->format('Y-m-d H:i:s')."'
				GROUP BY
					chat_id
				ORDER BY
					created DESC
				LIMIT ". $limit;
			
		}

		// perform the query
		$array = db_assoc_array(db_query($query));
		
		// IF the array is not empty
		if(!empty($array)){
			
			// make a new array object
			$ret = array();
		
			// FOREACH of the scheduled
			foreach($array as $num => $assoc){
			
				// push the object onto the array
				array_push($ret, self::ChatFromArray($assoc));
			
			}
			
			return $ret;
		}
		
		return array();
	}

}
?>

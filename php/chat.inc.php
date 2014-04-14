<div style="float: right; width: 400px; margin: 120px auto auto; background-color: rgb(255, 255, 255); min-height: 1000px;">
    <h2 style="padding: 5px; border-bottom: 1px solid rgb(247, 247, 247);">Chat</h2>
    <div id="chat_thread">
    	<form id="chatBoxForm" action="ajaxChat.php" name="chat" method="post" enctype="multipart/form-data">
    		<input type="hidden" name="name" value="<?=$user->getName()?>">
        	<table class="response" width="360" border="0" style="border-collapse: collapse; margin-left: auto; margin-right: auto;">
            	<tr>
                	<th>Chat Message</th>
            	</tr>
            	<tr>
                	<td>
                    	<textarea id="chatBox" name="content" cols="40" rows="4" placeholder="post a chat message to others"></textarea>
                	</td>
            	</tr>
            	<tr>
                	<td>
                    	<input class="btn_sm" type="button" value="Send Chat" onclick="onChatButtonClick()"></input>
                	</td>
            	</tr>
        	</table>
        </form>
        <div id="chats">
        <?
			// 10 showing
			$count = 15;

			// get all the chats
        	$chats = Chat::getChats($count, null, null);
		
			// FOR up to 10 chats
			foreach($chats as $num => $current){?>
        		<table id="<?=$current->chat_id?>" class="<?=($current->name == $user->getName()) ? "response" : "message"?>" width="360" border="0" style="border-collapse: collapse; margin-left: auto; margin-right: auto;">
    				<tr>
        				<th>
            				<span><?=$current->name?></span>
        				</th>
        				<th class="tmeta">
        					<?=$current->created?>
        				</th>
    				</tr>
    				<tr>
        				<td colspan="2">
        					<span class="timestamp" style="display:none;">
        						<?=$current->created?>
        					</span>	
        					<p class="pWithLineBreaks">
            					<?=$current->content?>
            				</p>
        				</td>
    				</tr>
				</table>
			<?}?>
		</div>
    </div>
</div>
<br />
<br />
<!-- Chat Meta Data  -->
<script>
var myName = "<?=$user->getName()?>";
</script>
<script src="./js/chat.js"></script>

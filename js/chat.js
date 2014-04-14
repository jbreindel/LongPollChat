/******************************************************************************
 *
 * FILE:                chat.js
 * AUTHOR:              Jake Breindel
 * DATE:                4-7-2014
 *
 * DESRIPTION:
 *  javascript file for allowing chat functionality on the site.
 *
 ******************************************************************************/

// the current time
var now = moment();
// chat endpoint
var chatUrl = "/ajaxChat.php";
// chats that are already showing
var childrenChats;

// make a new poller
var poller = new Timer(poll, 1000);

// update the timestamps when the docuemnt loads
$( document ).ready(updateTimeStamps());

/**
 * keeps linebreaks on text area submission 
 */
function keepLB (str) { 
  var reg=new RegExp("(%0A)", "g");
  return str.replace(reg,"%0D$1");
}

/**
 * timer class for execution based on time events
 */
function Timer(callback, delay) {
    var timerId, start, remaining = delay;

    this.pause = function() {
        window.clearTimeout(timerId);
        remaining -= new Date() - start;
    };

    this.resume = function() {
        timerId = window.setTimeout(callback, remaining);
    };

    this.resume();
}

/**
 * Polls the server for new  
 */
function poll(){
    $.get(chatUrl, {
            "start" : now.format("YYYY-MM-DD HH:mm:ss"),
            "end" : moment().format("YYYY-MM-DD HH:mm:ss")
        }, onPollComplete);
}

/**
 * Called when the poll completes
 */
function onPollComplete(data, textStatus, jqXHR) {
    var jsonArray = $.parseJSON(data);
    var chat;
    for (var i = jsonArray.length - 1; i >= 0; i--) {
        chat = jsonArray[i];
        if (!($("#chats").find("#" + jsonArray[i].chat_id).length)) {
            $.titleAlert("New chat message!", {
                    requireBlur:false,
                    stopOnFocus:true,
                    duration:5000,
                    interval:700
                });
            $("#chats").prepend(buildChatBox(chat));
        }
    }
    updateTimeStamps();
    now = moment();
    poller = new Timer(poll, 1000);
}

/**
 * called when the post comment button is clicked.
 */
function onChatButtonClick() {
    $.post(chatUrl, keepLB($("#chatBoxForm").serialize()), onChatPostComplete);
}

/**
 * called after a new chat has been sent to the server.
 */
function onChatPostComplete(data, textStatus, jqXHR) {
    $("#chatBox").value = "";
    $("#chats").empty();
    var jsonArray = $.parseJSON(data);
    buildChatList(jsonArray);
    now = moment();
    updateTimeStamps();
}

/**
 * refreshed the chat list with the provided data list
 */
function buildChatList(jsonArray) {
    jQuery.each(jsonArray, function() {
        $("#chats").append(buildChatBox(this));
    });
}

/**
 * builds a chat box from a chat object
 */
function buildChatBox(chat) {
    var chatHtml = "<table id=\"" + chat.chat_id + "\" class=\"" + ((chat.name == myName) ? "response" : "message") + "\" width=\"360\" border=\"0\" >";
    chatHtml += "<tr><th><span>";
    chatHtml += chat.name;
    chatHtml += "</span></th><th class=\"tmeta\">";
    chatHtml += chat.created;
    chatHtml += "</th></tr><tr><td colspan=\"2\"><span class=\"timestamp\" style=\"display:none;\">" + chat.created + "</span><p class=\"pWithLineBreaks\">";
    chatHtml += chat.content;
    chatHtml += "</p></td></tr></table>";
    return chatHtml;
}

/**
 * updates the timestamps on the chat messages 
 */
function updateTimeStamps(){
    var timestamp, display;
    $("#chats > table").each(function(){
        timestamp = $(this).find(".timestamp").text();
        display = $(this).find(".tmeta");
        var created = moment(timestamp, "YYYY-MM-DD HH:mm:ss");
        display.html(created.fromNow());
    });
}

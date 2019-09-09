<?php

/*******************************************
 * 
 * https://t.me/DeleteAllMessageILBOT
 * 
 * Created by @YehudaEisenberg
 *  
*******************************************/

define('BOT', array(
    "token" => "<TOKEN>",
    "webHookUrl" => "https://telegram.org/DeleteAllMessageILBOT.php",
    "debug" => false
    ));
require_once("BotClass.php");

define('BOT_ID', NULL);

$bot->SetUpdate($update);
$bot->SetParseMode('MarkDown');

$ncpId = isset($update["message"]["new_chat_participant"]["id"])?$update["message"]["new_chat_participant"]["id"]:null;

if($chatType == "supergroup" && $ncpId == BOT_ID){
    $bot->sendMessage($chatId, "שלום לכולם 👋🏼 \nמעכשיו, כל הודעה שתשלח בקבוצה זו תמחק.");
    $bot->sendMessage($chatId, "*חובה להגדיר אותי כמנהל כדי שאני אוכל לעבוד*");
}
elseif($chatType == "supergroup")
    $bot->deleteMessage($chatId, $messageId);
elseif($chatType == "private"){
	$bot->sendMessage($chatId, "היי 👋🏼\nאני מוחק כל הודעה שנשלחת בקבוצה! כולל הכל!\n➕ להוספת הרובוט לקבוצה [לחץ כאן](http://t.me/DeleteAllMessageILBOT?startgroup=true). \n📣 לערוץ 'ממלכת הרובוטים הישראלית' [לחץ כאן](t.me/IL_BOTS). ");
    $bot->sendMessage($chatId, "*נ.ב.\nחובה להגדיר אותי כמנהל כדי שאני אוכל לעבוד*");
}
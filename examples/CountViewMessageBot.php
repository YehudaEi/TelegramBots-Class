<?php

/*******************************************
 * 
 * https://t.me/CountViewMessageBot
 * 
 * Created by @YehudaEisenberg
 *  
*******************************************/

define('BOT', array(
    "token" => "<TOKEN>",
    "webHookUrl" => "https://telegram.org/CountViewMessageBot.php",
    "debug" => false
    ));
require_once("BotClass.php");

define('CHANNEL_ID', NULL);

if($message == "/start")
    $bot->sendMessage($chatId, "רובוט 🤖 זה יעזור לכם לגלות את מספר האנשים שצפו👁 לכם בהודעה💬.\nפשוט תשלחו הודעה ותקבלו אותה בחזרה עם מונה הצפיות.😎\nבנוסף, אם תוסיפו את הרובוט לקבוצה ותיתנו לו ניהול למחיקת הודעות, כל ההודעות שישלחו בקבוצה יהיו עם מונה צפיות!\nנוצר ע\"י @YehudaEisenberg");
else{
    $res = $bot->forwardMessage(CHANNEL_ID, $chatId, $messageId);
    
    if($chatType == "supergroup"){
        if($bot->deleteMessage($chatId, $messageId)['ok'])
            $bot->forwardMessage($chatId, CHANNEL_ID, $res['result']['message_id']);
    }
    else
        $bot->forwardMessage($chatId, CHANNEL_ID, $res['result']['message_id']);
    $bot->deleteMessage(CHANNEL_ID, $res['result']['message_id']);
}
<?php

/*******************************************
 * 
 * https://t.me/RandomFaceBot
 * 
 * Created by @YehudaEisenberg
 *  
*******************************************/

define('BOT', array(
    "token" => "<TOKEN>",
    "webHookUrl" => "https://telegram.org/RandomFaceBot.php",
    "allowed_updates" => array ("message", "edited_message"),
    "debug" => false
    ));
require_once("BotClass.php");
$bot->SetUpdate($update);
$bot->SetParseMode('MarkDown');

$markup = json_encode(array('inline_keyboard' => array(array(array('text' => 'Get Photo | קבל תמונה', 'callback_data' => "GetPhoto"))), 'one_time_keyboard' => true,'resize_keyboard' => true));

if($callData == "GetPhoto"){
    $name = uniqid().".jpg";
    copy("https://thispersondoesnotexist.com/image", $name);
    $bot->sendPhoto($chatId, new CURLFile(realpath($name)), "@RandomFaceBot", null, $markup);
    unlink($name);
}
elseif($chatType == "private")
    $bot->sendMessage($chatId, "היי,\nאני אשלח לך פנים שלא באמת קיימות!\nרק תלחץ על הכפתור..\nנוצר ע\"י @YehudaEisenberg\n-----------------------------\nHey,\nI'll send you a not exist face.\nJust press the button..\n\nCreated by @YehudaEisenberg.", $markup, $messageId);

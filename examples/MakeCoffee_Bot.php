<?php

/*******************************************
 * 
 * https://t.me/MakeCoffee_Bot
 * 
 * Created by @YehudaEisenberg
 *  
*******************************************/

define('BOT', array(
    "token" => "<TOKEN>",
    "webHookUrl" => "https://telegram.org/MakeCoffee_Bot.php",
    "debug" => false
    ));
require_once("BotClass.php");

$bot->SetUpdate($update);
$rm = array('inline_keyboard' =>  array(array(array('text' => 'הכנת קפה בצ\'אט חיצוני ☕', 'switch_inline_query' => ""))));

if(isset($message) && $message == "/start"){
    $bot->sendMessage($chatId, "אני מסכים להכין קפה רק בקבוצות, למה זה על חשבונכם?! 😜\n\n(תמיד ניתן לכפות עליי דברים שאני לא מסכים😂)", json_encode($rm));
}
elseif(isset($InlineQId)){
    $addedText = strlen($inlineQ) > 1 && strlen($inlineQ) < 100 ? "\n\nהמלצר רצה להוסיף לך משפט קצרצר: ".$inlineQ : "";
    $inlineRes[] = array(
            "type" => "article",
            "id" => "1",
            "title" => "לקבלת כוס קפה לחץ כאן ☕",
            "description" => "כרגע המלצרים קצת עצלנים ואי אפשר לבחור כמה סוכר😜",
            "message_text" => "הנה הקפה שלך אדוני ☕\nתהנה 😋😎".$addedText,
    );
    $bot->answerInlineQuery($InlineQId, json_encode($inlineRes));
}
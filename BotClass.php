<?php

/***********************************************
 * 
 * PHP-Telegram-BOT class
 * 
 * Owner: Yehuda Eisenberg.
 * 
 * Mail: Yehuda.telegram@gmail.com
 * 
 * Link: https://yehudae.ga
 * 
 * Telegram: @YehudaEisenberg
 * 
 * GitHub: https://github.com/YehudaEi
 *
 * License: MIT - אסור לעשות שימוש ציבורי, חובה להשאיר קרדיט ליוצר
 * 
************************************************/


/***********************************************
 * 
 * define('BOT', array(
 *   "token" => "<BOT_TOKEN>",
 *   "webHookUrl" => "https://telegram.org/Bot.php",
 *   "allowed_updates" => array("message", "edited_message"),
 *   "debug" => false
 * ));
 * require_once("BotClass.php");
 * 
***********************************************/
// used by error heandlig function
define("ME", <yourtelegram-id>);
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Jerusalem');
if(!defined('DATA_PATH'))
    define('DATA_PATH', '/var/telegram-bots/BotsDATA/');
if(!defined('WEBMASTER_TG_ID'))
    define('WEBMASTER_TG_ID', '560402434');

$update = json_decode(file_get_contents('php://input'), true); 
if(($update == NULL || !defined('BOT')) && !defined('SEND_MESSAGE')){
    http_response_code(403);
    include '403.html';
    die();
}


if(!BOT['debug'])
    error_reporting(0);

global $bot;
$bot = new Bot(BOT['token'], BOT['debug']);


$up_type = array_keys($update)[1];

// the callback update contain the message update 
if(isset($update['callback_query'])){
    // the clicker data
    $callFromId = $update["callback_query"]['from']['id'];
    $callId = $update["callback_query"]["id"];
    $callData = $update["callback_query"]["data"];

    // update the update to $update[up_type]{update body}
    $update['callback_query'] = $update['callback_query']['message'];
}else{
    $data = null;
}

// global vars for all kinds of updates
$userName = $update[$up_type]["chat"]["username"]                   ?? null;
$chatId = $update[$up_type]["chat"]["id"]                           ?? null;
$FirstName = $update[$up_type]["chat"]["first_name"]            	?? null;
$LastName = $update[$up_type]["chat"]["last_name"]              	?? null;

$fromId = $update[$up_type]["from"]["id"]                			?? null;
$fromUserName = $update[$up_type]["from"]["username"]               ?? null;
$fromFirstName = $update[$up_type]["from"]["first_name"]            ?? null;
$fromLastName = $update[$up_type]["from"]["last_name"]              ?? null;

// $cid = $update[$up_type]["chat"]["id"]                           ?? null;
$chatType = $update[$up_type]["chat"]["type"]                       ?? null;
$message = $update[$up_type]["text"] ?? $update[$up_type]['caption']?? null;
$messageId = $update[$up_type]['message_id']                        ?? null;
$title = $update[$up_type]["chat"]["title"]                         ?? null;

$cap = $update[$up_type]['caption']                                 ?? null;

// forward
$forwrdId = $update[$up_type]['forward_from']['id']                 ?? null;
$forwrdFN = $update[$up_type]['forward_from']['first_name']         ?? null;
$forwrdLN = $update[$up_type]['forward_from']['last_name']          ?? null;
$forwrdUN = $update[$up_type]['forward_from']['username']           ?? null;

$fwdFrom = $update[$up_type]['forward_from_chat']['id']             ?? null;


// replay
$rtmid = $update[$up_type]['reply_to_message']['message_id']        ?? null;
$rtmt = $update[$up_type]['reply_to_message']['text']               ?? null;

//Inline
$inlineQ = $update["inline_query"]["query"]                          ?? null;
$InlineQId = $update["inline_query"]["id"]                           ?? null;
$fromId = $update["inline_query"]["from"]["id"]						 ?? null;

$ent = $update[$up_type]['entities']                                ?? null;

$buttons = $update[$up_type]["reply_markup"]["inline_keyboard"]     ?? null;

// general data for all kind of files
// there is also varibals for any kind below, you can use them both or delete one of them
$general_file = null;
$fileTypes = ['photo', 'video', 'document', 'audio', 'sticker', 'voice', 'video_note'];
foreach($fileTypes as $type){
    if(isset($update[$up_type][$type])){
        if($type == "photo"){
            $general_file = $update[$up_type]['photo'][count($update[$up_type][$type])-1];
        }else
            $general_file = $update[$up_type][$type];
		// you cat also define mime type, not complate yet.(not to all files there is mime type, you cat get  it by download the file and use bild-in function or use the get_download_info method of madelineProto)
        // if(isset($general_file['mime_type'])){
        //     $general_file['ext'] = ".".str_replace($type."/", "", $general_file['mime_type']);
        // }
    }
}

// Individual variables

//photo
$tphoto = $update[$up_type]['photo']                                ?? null;
if(!empty($tphoto))
    $phid = $update[$up_type]['photo'][count($tphoto)-1]['file_id'] ?? null;
//audio
$auid = $update[$up_type]['audio']['file_id']                       ?? null;
$duration = $update[$up_type]['audio']['duration']                  ?? null;
$autitle = $update[$up_type]['audio']['title']                      ?? null;
$performer = $update[$up_type]['audio']['performer']                ?? null;
//document
$did = $update[$up_type]['document']['file_id']                     ?? null;
$dfn = $update[$up_type]['document']['file_name']                   ?? null;
//video
$vidid = $update[$up_type]['video']['file_id']                      ?? null;
//voice 
$void = $update[$up_type]['voice']['file_id']                       ?? null;
//video_note
$vnid = $update[$up_type]['video_note']['file_id']                  ?? null;
//contact
$conph = $update[$up_type]['contact']['phone_number']               ?? null;
$conf = $update[$up_type]['contact']['first_name']                  ?? null;
$conl = $update[$up_type]['contact']['last_name']                   ?? null;
$conid = $update[$up_type]['contact']['user_id']                    ?? null;
//location
$locid1 = $update[$up_type]['location']['latitude']                 ?? null;
$locid2 = $update[$up_type]['location']['longitude']                ?? null;
//Sticker
$stid = $update[$up_type]['sticker']['file_id']                     ?? null;
//Venue
$venLoc1 = $update[$up_type]['venue']['location']['latitude']       ?? null;
$venLoc2 = $update[$up_type]['venue']['location']['longitude']      ?? null;
$venTit = $update[$up_type]['venue']['title']                       ?? null;
$venAdd = $update[$up_type]['venue']['address']                     ?? null;


// if thete ent in text its revers it to markdown and add `/```/*/_ to text
$realtext = null;
if($ent != null){
    $i = 0;
    $realtext = $message;
    foreach($ent as $e){
        switch($e['type']){
            case "code":
                $replacment = "`";
            break;
            case "pre":
                $replacment = "```";
            break;
            case "bold":
                $replacment = "*";
            break;
            case "italic":
                $replacment = "_";
            break;
            default:
                continue 2;
        }
        $realtext = ent_replace($realtext, $replacment, $e['offset'], $e['length'], $i);
        $i += strlen($replacment)*2;
    }
}
function ent_replace($text, $replace, $offset, $length, $delay){
    $text = substr_replace($text, $replace, $offset+$delay, 0);
    return substr_replace($text, $replace, $offset+$length+strlen($replace)+$delay, 0);
}


if(isset($chatType) && isset($chatId))
    $bot->SaveID($chatId, $chatType);

class Bot{
    private $BotToken;
    private $BotId;
    private $BotName;
    private $BotUserName;
    private $DBName;
    private $Debug;
    private $beautifi = true;
    private $update = null;
    private $webHook = null;
    private $webPagePreview = true;
    private $Notification = false;
    private $ParseMode = null;

    public function Bot($token, $Debug = false){
        $botInfo = json_decode(file_get_contents("https://api.telegram.org/bot".$token."/getMe"), true);
        if($Debug && 0)
            $this->logging($botInfo, false, "BotInfoOutput: Success!", true);
        if($botInfo['ok'] == true && $botInfo['result']['is_bot'] == true){
            $this->BotToken = $token;
            $this->Debug = $Debug;
            $this->BotId = $botInfo['result']['id'];
            $this->BotName = $botInfo['result']['first_name'];
            $this->BotUserName = $botInfo['result']['username'];
            $this->DBName = DATA_PATH.$botInfo['result']['id']." - ".$this->BotUserName.'.sqlite';
            
            //$old_db_name = DATA_PATH.$this->BotUserName.'.sqlite';
            //if(isset($old_db_name)){
            //    copy($old_db_name, $this->DBName);
            //    unlink($old_db_name);
            //}
                
            //Update WebHook
            $res = $this->Request("getwebhookinfo");
            if($res['result']['url'] != BOT['webHookUrl'])
                $this->Request("setwebhook", array('url' => BOT['webHookUrl']));
            return true;
        }
        else return false;
    }
    
    private function DB($q){
        try{
            $DBConn = new SQLite3($this->DBName , SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE) 
                    or $this->sendMessage(WEBMASTER_TG_ID, 'error conncet to db. username:'.$this->BotUserName);
            $DBConn->query('CREATE TABLE IF NOT EXISTS "users" (
                        "user_id" INT(11) PRIMARY KEY,
                        "type" VARCHAR,
                        "time" TIMESTAMP
                    )');
            $DBConn->query($q);
            $DBConn->close();
        }
        catch(Exception $e){
            
        }
    }
    
    public function SaveID($id, $type){
        $this->DB('INSERT OR IGNORE INTO "users" ("user_id", "type", "time")
            VALUES ("'.$id.'", "'.$type.'" ,"'.time().'")');
    }
    
    //Setters && Getters
        //Debug Mode
    public function GetDebug(){
        return $this->Debug;
    }
    public function SetDebug($val){
        $this->Debug = $val;
    }
        //WebHook
    public function GetWebHook(){
        return $this->webHook;
    }
    public function SetWebHook($val){
        $this->webHook = $val;
        return $this->Request('setwebhook', array("url" => $val, "allowed_updates" => BOT['allowed_updates']))['ok'];
    }
    public function DetWebHook(){
        $this->webHook = NULL;
        return $this->Request('setwebhook', array("url"))['ok'];
    }
        //Updates - BETA!
    public function SetUpdate($update){
        $this->Update = $update;
        if($this->Debug)
            $this->logging($update, false, "Update input:", true);
    }
    public function GetUpdate(){
        return $this->Update;
    }
        //WebPagePreview Mode
    public function GetWebPagePreview(){
        return $this->webPagePreview;
    }
    public function SetWebPagePreview($val){
        $this->webPagePreview = $val;
    }
        //Notification Mode
    public function GetNotification(){
        return $this->Notification;
    }
    public function SetNotification($val){
        $this->Notification = $val;
    }
        //ParseMode Mode
    public function GetParseMode(){
        return $this->ParseMode;
    }
    public function SetParseMode($val){
        if("markdown" == strtolower($val) || "html" == strtolower($val) || null == $val)
            $this->ParseMode = $val;
    }
        //DBName
    public function GetDBName(){
        return $this->DBName;
    }
    public function getFullBotInfo($token){
        $botInfo = json_decode(file_get_contents("https://api.telegram.org/bot".$token."/getMe"), true);
        $botWebhookInfo = json_decode(file_get_contents("https://api.telegram.org/bot".$token."/getWebHookInfo"), true);
        if($botInfo['ok'] == true){
            $botInfo['result']['webHookInfo'] = $botWebhookInfo['result'];
            return $botInfo['result'];
        }
        return false; 
    }
    //SendRequest
    private function Request($method, $data =[] ==null){
        $BaseUrl = "https://api.telegram.org/bot".$this->BotToken."/".$method;
    	
        $ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $BaseUrl);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch ,CURLOPT_POSTFIELDS, $data);
       
        $res = curl_exec($ch);
        if(curl_error($ch)){
            if($this->Debug)
                $this->logging(curl_error($ch), "Curl: ".$method, false, false, $data);
    		curl_close($ch);
        }else{
			
            curl_close($ch);
            $res = json_decode($res, true);
			// you can send to yor self the error details
			//if(!$res['ok']){
            //    return $this->error_heandler($res);
            //}
            if($this->Debug)
                $this->logging($res, "Curl: ".$method, true, true, $data);
            return $res;
        }
    }
    
    //Logging
    public function logging($data, $method = null, $success = false, $array = false, $helpArgs = null){
        $tmp = ($this->beautifi ? JSON_PRETTY_PRINT : null ) | JSON_UNESCAPED_UNICODE;
        if(!$array)
            $data = array("data" => $data);
        
        $data['added_by_log']['helpArgs'] = $helpArgs;
        $data['added_by_log']['date'] = date(DATE_RFC850);
        $data['added_by_log']['botUserName'] = $this->BotUserName;
        $data['added_by_log']['success'] = ($success ? "Success!" : "Error");
        $data['added_by_log']['method'] = $method;
        
        $data = json_encode($data, $tmp);
        file_put_contents($this->BotUserName." - log.log", $data.",\n", FILE_APPEND | LOCK_EX);
    }
    
    //Methods
    public function sendMessage($id, $text, $replyMarkup = null, $replyMessage = null){
        $data["chat_id"] = $id;
        $data["text"] = $this->text_adjust($text);
        $data["parse_mode"] = $this->ParseMode;
        $data["disable_web_page_preview"] = $this->webPagePreview;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("sendMessage", $data);
    }
    public function forwardMessage($id, $fromChatId, $messageId){
        $data["chat_id"] = $id;
        $data["from_chat_id"] = $fromChatId;
        $data["disable_notification"] = $this->Notification;
        $data["message_id"] = $messageId;
        return $this->Request("forwardMessage", $data);
    }
    public function sendPhoto($id, $photo, $caption = null, $replyMessage = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["photo"] = $photo;
        $data["caption"] = $this->text_adjust($caption);
        $data["parse_mode"] = $this->ParseMode;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("sendPhoto", $data);
    }
    public function sendAudio($id, $audio, $duration = null, $performer = null, $title = null, $replyMessage = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["audio"] = $audio;
        $data["duration"] = $duration;
        $data["performer"] = $performer;
        $data["title"] = $title;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("sendAudio", $data);
    }
    public function sendDocument($id, $document, $caption = null, $replyMessage = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["document"] = $document;
        $data["caption"] = $this->text_adjust($caption);
        $data["parse_mode"] = $this->ParseMode;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("sendDocument", $data);
    }
    public function sendSticker($id, $sticker, $replyMessage = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["sticker"] = $sticker;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("sendSticker", $data);
    }
    public function sendVideo($id, $video, $caption = null, $duration = null, $width = null, $height = null, $replyMessage = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["video"] = $video;
        $data["duration"] = $duration;
        $data["width"] = $width;
        $data["height"] = $height;
        $data["caption"] = $this->text_adjust($caption);
        $data["parse_mode"] = $this->ParseMode;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("sendVideo", $data);
    }
    public function sendVoice($id, $voice, $duration = null, $replyMessage = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["voice"] = $voice;
        $data["duration"] = $duration;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("sendVoice", $data);
    }
    public function sendLocation($id, $latitude, $longitude, $replyMessage = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["latitude"] = $latitude;
        $data["longitude"] = $longitude;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("sendLocation", $data);
    }
    public function sendVenue($id, $latitude, $longitude, $title, $address, $foursquare = null, $replyMessage = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["latitude"] = $latitude;
        $data["longitude"] = $longitude;
        $data["title"] = $title;
        $data["address"] = $address;
        $data["foursquare_id"] = $foursquare;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("sendVenue", $data);
    }
    public function sendContact($id, $phoneNumber, $firstName, $lastName = null, $replyMessage = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["phone_number"] = $phoneNumber;
        $data["first_name"] = $firstName;
        $data["last_name"] = $lastName;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("sendContact", $data);
    }
    public function sendChatAction($id, $action){
        if(!in_array($action, ["typing", "upload_photo", "record_video", "upload_video", "record_audio", "upload_audio", "upload_document", "find_location"]))
            return false;
        $data["chat_id"] = $id;
        $data["action"] = $action;
        return $this->Request("sendChatAction", $data);
    }
    public function getUserProfilePhotos($uId, $offset = null, $limit = null){
        $data["user_id"] = $uId;
        $data['offset'] = $offset;
        $data['limit'] = $limit;
        return $this->Request("getUserProfilePhotos", $data);
    }
    public function kickChatMember($id, $uId){
        $data["chat_id"] = $id;
        $data["user_id"] = $uId;
        return $this->Request("kickChatMember", $data);
    }
    public function unbanChatMember($id, $uId){
        $data["chat_id"] = $id;
        $data["user_id"] = $uId;
        return $this->Request("unbanChatMember", $data);
    }
    public function getFile($fileId){
        $data["file_id"] = $fileId;
        return $this->Request("getFile", $data);
    }
    public function leaveChat($id){
        $data["chat_id"] = $id;
        return $this->Request("leaveChat", $data);
    }
    public function getChat($id){
        $data["chat_id"] = $id;
        return $this->Request("getChat", $data);
    }
    public function getChatAdministrators($id){
        $data["chat_id"] = $id;
        return $this->Request("getChatAdministrators", $data);
    }
    public function getChatMembersCount($id){
        $data["chat_id"] = $id;
        return $this->Request("getChatMembersCount", $data);
    }
    public function getChatMember($id, $uId){
        $data["chat_id"] = $id;
        $data["user_id"] = $uId;
        return $this->Request("getChatMember", $data);
    }
    public function answerCallbackQuery($callback, $text = null, $alert = false){
        $data["callback_query_id"] = $callback;
        $data["text"] = $this->text_adjust($text);
        $data["show_alert"] = $alert;
        return $this->Request("answerCallbackQuery", $data);
    }
    public function editMessageText($id = null, $messageId = null, $inlineMessage = null, $text, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["message_id"] = $messageId;
        $data["inline_message_id"] = $inlineMessage;
        $data["text"] = $this->text_adjust($text);
        $data["parse_mode"] = $this->ParseMode;
        $data["disable_web_page_preview"] = $this->webPagePreview;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("editMessageText", $data);
    }
    public function editMessageCaption($id = null, $messageId = null, $inlineMessage = null, $caption = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["message_id"] = $messageId;
        $data["inline_message_id"] = $inlineMessage;
        $data["caption"] = $this->text_adjust($caption);
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("editMessageCaption", $data);
    }
    public function editMessageMedia($id = null, $messageId = null, $inlineMessage = null, $media = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["message_id"] = $messageId;
        $data["inline_message_id"] = $inlineMessage;
        $data["media"] = $media;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("editMessageMedia", $data);
    }
    public function editMessageReplyMarkup($id = null, $messageId = null, $inlineMessage = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["message_id"] = $messageId;
        $data["inline_message_id"] = $inlineMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("editMessageReplyMarkup", $data);
    }
    public function deleteMessage($id, $messageId){
        $data["chat_id"] = $id;
        $data["message_id"] = $messageId;
        return $this->Request("deleteMessage", $data);
    }
    public function answerInlineQuery($inlineMessage, $res, $cacheTime = null, $isPersonal = null, $nextOffset = null, $switchPmText = null, $switchPmParameter = null){
        $data["inline_query_id"] = $inlineMessage;
        $data["results"] = $res;
        $data["cache_time"] = $cacheTime;
        $data["is_personal"] = $isPersonal;
        $data["next_offset"] = $nextOffset;
        $data["switch_pm_text"] = $switchPmText;
        $data["switch_pm_parameter"] = $switchPmParameter;
        return $this->Request("answerInlineQuery", $data);
    }
	// helpers
	
	// parepare the text to avoid send errors
	public function text_adjust($text){
        $type = gettype($text);
        if($type == "array")
            $text = json_encode($text,TRUE | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        elseif($type == "NULL")
            $text = "text is NULL";

        if(strlen($text) > 4048)
            $text = "message is too long. https://del.dog/".json_decode(($this->post("https://del.dog/documents", $text), true)["key"];
        elseif($text == '')
            $text = "message empty";

        if($this->ParseMode == "markdown" && preg_match_all('/(@|[^\(]http)\S+_\S*/', $text, $m) != 0){
            foreach($m[0] as $username){
                $text = str_replace($username, str_replace('_', "\_", $username), $text);
            }
        }
        return $text;
    }
	
	// bilde inline keyboard from array
	// argument is [ [button=>data, button2=>data ], /*row 2*/[button=>data, button2=>data ] ]
	// by defult the button type is callback_data, you can also set button to url button by [ [text_button => [ "url" => link], callback=>data ] ]
	public function keyboard($data){
        $a = [];
        $c = [];
        foreach($data as $row){
            foreach($row as $key => $value){
                if(gettype($value) == "array"){
                    $k = key($value);
                    array_push($c, ['text'=>$key, $k => $value[$k]]);
                }else
                    array_push($c, ['text'=>$key,'callback_data'=>"$value"]);
            }
            array_push($a, $c);
            $c = [];
        }
        return json_encode(array('inline_keyboard' => $a)); 
    }
	// if you too laze to open logs to chack what happend you can send to your self the errors
	public function error_handler($respons){
        $this->SetParseMode();
        if($respons['error_code'] == 429){
            $this->sendMessage(ME, "flood, wait ".$respons['parameters']['retry_after']. " seconds");
            die();
            //sleep($respons['parameters']['retry_after']);
        }elseif(strpos($respons['description'], "can't parse entities") !== 0){
           // file_put_contents("text.txt", debug_backtrace()[2]["args"][1]);
           // $this->sendDocument(ME, new CURLFile("text.txt"));
           // unlink("text.txt");
            // $this->sendMessage(ME, $respons['description']);
        }elseif($respons['error_code'] == 403){
            $this->sendMessage(ME, "forrbiden ".debug_backtrace()[2]["args"][0]);
        }
        foreach (debug_backtrace() as $key => $value) {
            if($key == 0)
                continue;
            if($value['function'] == "error_heandler"){
                $this->sendMessage(ME, "loop error");
                $this->sendMessage(ME, $respons['description']);
                //throw new Exception("error loop/n", 1);
                die();
            }
        }
        global $update;
        $respons["call_by"] = debug_backtrace()[2]['function'];
        $respons["from_line"] = debug_backtrace()[2]['line'];
        $respons["_"] = "error output";
        $respons['update'] = $update;

        $this->sendMessage(ME, $respons);
        return $respons;
    }
	// if text to long this is help function to send the text to del.dog
    function post($url, $postVars = array()){

        if(gettype($postVars) == "array"){
            $postVars = http_build_query($postVars);
        }
        $options = array(
            'http' =>
                array(
                    'method'  => 'POST', 
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postVars
                )
        );
        $streamContext  = stream_context_create($options);
        
        $result = file_get_contents($url, false, $streamContext);
        
        if($result === false){
            $error = error_get_last();
            //throw new Exception('POST request failed: ' . $error['message']);
        }
        return $result;
    }
}
/*
uncomment this function to get the php errors in telegram
set_exception_handler("error_handler");
function error_handler($e){
    global $bot;
    $r["file"] = $e->getFile();
    $r["error"] = $e->getMessage();
    $r["line"] = $e->getLine();
    $bot->sendMessage(ME, $r);
}
*/

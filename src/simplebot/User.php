<?php

namespace simplebot;

use simplebot\utils\Utils;
use simplebot\event\EventManager;
use simplebot\event\user\OutcomingMessageEvent;

class User {
    
    private $uid;
    private $bot;
    private $mgr;

    public function __construct(Bot $bot, $uid, EventManager $mgr) {
        $this->bot = $bot;
        $this->uid = $uid;
        $this->mgr = $mgr;
    }
    
    public function sendMessage($text, array $attachments = []){
        $baseParams = [
            'access_token' => $this->bot->config->get('token'),
            'v' => 5.87,
            'message' => $text,
            'user_id' => $this->uid
        ];
        
        foreach($attachments as $type => $body){
            $baseParams[$type] = $body;
        }
        
        $params = http_build_query($baseParams);
        $url = 'https://api.vk.com/method/messages.send?'.$params;

        $this->mgr->callEvent($e = new OutcomingMessageEvent($this, $text));
        if(!$e->cancelled()){
            Utils::getURL($url);
        }
    }
    

    public function getId(){
        return $this->uid;
    }
}

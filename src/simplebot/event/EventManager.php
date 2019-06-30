<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace simplebot\event;

use simplebot\event\user\IncomingMessageEvent;
use simplebot\event\BaseEvent;
use simplebot\Bot;
use simplebot\User;
use simplebot\plugin\PluginPool;

class EventManager {
    
    private static $list = [];
    
    private $bot;
    
    public static function register($name, $event){
        self::$list[$name] = $event;
    }

    public function __construct(Bot $bot) {
        $this->bot = $bot;
    }

    public function callEvent(BaseEvent $e){
        if(!isset(self::$list[$e->getName()])){
            $name = (new \ReflectionObject($e))->getShortName();
            echo '[EventManager] Сalling an unregistered event ', $name, "\n";
            return;
        }
        foreach($e::$handlers as $methods){
            //foreach($methods as $method){
                $methods[0]->{$methods[1]}($e);
            //}
        }
    }
    
    public function handleReceivedData(array $data){
        $updates = $data['updates'][0];
        
        switch($updates['type']){
            case 'message_new':
                $usr = new User($this->bot, $updates['object']['from_id'], $this);
                $this->callEvent(new IncomingMessageEvent($usr, $updates['object']['text'], $updates['object']['id']));
                unset($usr);  
            break;
        }
    }
}

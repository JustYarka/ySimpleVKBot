<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace simplebot\event\user;

use simplebot\event\BaseEvent;
use simplebot\User;

class IncomingMessageEvent extends BaseEvent{
    
    public static $handlers = [];

    private $message;
    private $user;
    private $id;
    
    public function __construct(User $usr, $message, $id) {
        $this->user = clone $usr;
        $this->message = $message;
        $this->id = $id;
    }
    
    public function getUser(){
        return $this->user;
    }
    
    public function getMessage(){
        return $this->message;
    }
    
    public function getName() {
        return 'message_new';
    }
    
    public function getMessageId(){
        return $this->id;
    }
}

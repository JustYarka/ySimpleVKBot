<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace simplebot\event\user;

use simplebot\event\BaseEvent;
use simplebot\User;

class OutcomingMessageEvent extends BaseEvent{
    
    public static $handlers = [];
    
    private $user;
    private $msg;

    public function __construct(User $usr, $message) {
        $this->user = clone $usr;
        $this->msg = $message;
    }
    
    public function getUser(){
        return $this->user;
    }
    
    public function getMessage(){
        return $this->msg;
    }
    
    public function getName() {
        return 'message_reply';
    }
}

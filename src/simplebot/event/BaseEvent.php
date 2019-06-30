<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace simplebot\event;

abstract class BaseEvent {
    
    public static $handlers= [];
    
    protected $cancelled = false;
    
    public function cancel($cancel = true){
        $this->cancelled = $cancel;
    }
    
    public function cancelled(){
        return $this->cancelled;
    }
    
    abstract public function getName();
}

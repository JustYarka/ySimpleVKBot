<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace simplebot\plugin;

abstract class Plugin {
    
    private $name;
    private $ver;

    abstract public function onEnable();
    
    abstract public function onLoad();
    
    public function setName($name){
        $this->name = $name;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function setVer($ver){
        $this->ver = $ver;
    }
    
    public function getVer(){
        return $this->ver;
    }
    
}

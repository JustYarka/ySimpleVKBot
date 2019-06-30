<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace simplebot\utils;

class Configuration {
    
    private $data = [];
    private $filename;

    public function __construct($filename) {
        $this->filename = $filename;
        $this->init();
    }
    
    private function init(){
        if(!file_exists($this->filename)){
            file_put_contents($this->filename, '');
        }
        $content = file_get_contents($this->filename);
        $this->data = yaml_parse($content);
    }
    
    public function get($key){
        return $this->data[$key];
    }
    
    public function set($key, $value){
        $this->data[$key] = $value;
    }
    
    public function delete($key){
        unset($this->data[$key]);
    }
    
    public function getAsArray(){
        return $this->data;
    }
    
    public function setAsArray(array $data){
        $this->data = $data;
    }

    public function save(){
        $content = yaml_emit($this->data);
        file_put_contents($this->filename, $content);
    }
}

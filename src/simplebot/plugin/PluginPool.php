<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace simplebot\plugin;

/**
 * Description of PluginPool
 *
 * @author Ярослав
 */
class PluginPool {
    
    private static $list = [];
    
    public static function put(Plugin $plugin){
        self::$list[$plugin->getName()] = clone $plugin;
    }
    
    public static function asList(){
        return self::$list;
    }
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace simplebot\plugin;

use simplebot\event\BaseEvent;

class PluginLoader {

    public function putPlugin(array $pluginInfo, $folder){
        $dirs = explode(DIRECTORY_SEPARATOR, __DIR__);
        array_pop($dirs);
        array_pop($dirs);
        array_pop($dirs);
        
        $dir = implode(DIRECTORY_SEPARATOR, $dirs).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.$pluginInfo['main'];
        include_once $dir.'.php';
        $dirs = explode(DIRECTORY_SEPARATOR, $dir);
        $mainClass = new $pluginInfo['main'];
        
        if($mainClass instanceof Plugin){
            echo '[PluginLoader] Checking ', $pluginInfo['name'], " (version ".$pluginInfo['version'].")...\n";
            $this->load($mainClass, $pluginInfo['name'], $pluginInfo['version']);
        }else{
            echo '[PluginLoader] Cannot load ', $pluginInfo['name'], "\n";
            echo '[PluginLoader] Main class in ', $pluginInfo['name'], " doesn`t extends Plugin class\n";
        }        
    }
    
    public function load(Plugin $plugin, $name, $ver){
        echo '[PluginLoader] Loading ', $name, " (version ".$plugin->getVer().")...\n";
        $plugin->setName($name);
        $plugin->setVer($ver);
        $plugin->onLoad();
        PluginPool::put($plugin);
        
        $rplug = new \ReflectionObject($plugin);
        foreach($rplug->getMethods() as $method){
            foreach($method->getParameters() as $param){
                $class = $param->getClass();
                if($class != null && $class->isSubclassOf(BaseEvent::class)){
                    $class->name::$handlers[] = [$plugin, $method->getName()];
                }
            }
        }
    }
    
    public function enable(){
        foreach(PluginPool::asList() as $plugin){
            $plugin->onEnable();
        }
    }
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace simplebot\plugin;

class BotInfoPluginParser {
  
    public function parse($pluginFolderName, $data){
        $data = yaml_parse($data);
        
        if(!isset($data['name'])){
            echo '[PluginLoader] Plugin in folder ', $pluginFolderName, " don`t have parameter 'name'\n";
            return null;
        }
        if(!isset($data['version'])){
            echo '[PluginLoader] Plugin ', $data['name'], " don`t have parameter 'version'\n";
            return null;
        }
        if(!isset($data['main'])){
            echo '[PluginLoader] Plugin ', $data['name'], " don`t have parameter 'main'\n";
            return null;
        }
        
        return $data;
    }
}

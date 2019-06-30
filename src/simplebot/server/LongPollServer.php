<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace simplebot\server;

use simplebot\utils\Utils;
use simplebot\event\EventManager;
use simplebot\Bot;

class LongPollServer {
    
    private $ts;
    private $server;
    private $key;
    
    private $bot;

    public function __construct(Bot $bot, $key, $server, $ts) {
        $this->bot = $bot;
        $this->key = $key;
        $this->server = $server;
        $this->ts = $ts;
    }
    
    public function run(){
        $mgr = new EventManager($this->bot);
        echo "[ServerData] Server started\n";
        while(true){
            $data = Utils::getURL($this->server.'?act=a_check&key='.$this->key.'&ts='.$this->ts.'&wait=9');
            $data = json_decode($data, true);
            
            if (!is_array($data)){
                continue;
            }

            if (isset($data['failed']) && $data['failed'] > 1) {
              $this->reconnect();
              continue;
            }
            $this->ts = $data['ts'];

            if ($data['updates'] == [])
                continue;
                        
            $mgr->handleReceivedData($data);
        }
    }
    
    public function reconnect(){
        echo "[ServerManager] Reconnect attempt\n";
        $data = Utils::getURL('https://api.vk.com/method/groups.getLongPollServer?access_token='.$this->token.'&group_id='.$this->gid.'&v=5.87');
        $data = json_decode($data, true);
        if (isset($json['error'])) {
            echo "Ошибка получения longpoll'а #{$json['error']['error_code']} : {$json['error']['error_msg']}\n";
            echo "Переподключение через 10 секунд\n";
            $time = time() + 10;
            while (time() < $time) {
              continue;
            }
            
            echo "[ServerManager] Reconnection failed\n";
            $this->reconnect();
            return;
        }
        
        $this->server = $data['response']['server'];
        $this->key = $data['response']['key'];
        $this->ts = $data['response']['ts'];
        echo "[ServerManager] Reconnect successfully\n";
    }
}

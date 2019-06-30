<?php

namespace simplebot;

use simplebot\utils\Configuration;
use simplebot\utils\Utils;
use simplebot\server\LongPollServer;
use simplebot\plugin\PluginLoader;
use simplebot\plugin\BotInfoPluginParser;
use simplebot\event\EventManager;
use simplebot\event\user\IncomingMessageEvent;
use simplebot\event\user\OutcomingMessageEvent;

class Bot {
    
    /**
     * @var Configration
     */
    public $config;
    
    private $token;
    private $gid;
    
    private static $instance;

    public function __construct() {
        $this->about();
        $this->initConfig();
        $this->initEvents();
        $this->token = $this->config->get('token');
        $this->gid = $this->config->get('groupId');
        $this->loadPlugins();
        $this->initServer();
        self::$instance = &$this;
    }

    public function initConfig(){
        $this->config = new Configuration($this->getResourcesFolder().'config.yml');
    }
    
    public function initEvents(){
        EventManager::register('message_new', IncomingMessageEvent::class);
        EventManager::register('message_reply', OutcomingMessageEvent::class);
    }

    public function initServer(){
        $data = Utils::getURL('https://api.vk.com/method/groups.getLongPollServer?access_token='.$this->token.'&group_id='.$this->gid.'&v=5.87');
        $data = json_decode($data, true);
        if (isset($json['error'])) {
            echo "Ошибка получения longpoll'а #{$json['error']['error_code']} : {$json['error']['error_msg']}\n";
            echo "Переподключение через 10 секунд\n";
            $time = time() + 10;
            while (time() < $time) {
              continue;
            }
            
            $this->initServer();
            return;
        }
        $ts = $data['response']['ts'];
        $serv = $data['response']['server'];
        $key = $data['response']['key'];
        printf("\n============================\n\n[LPData] ts: %s\n[LPData] Server: %s\n[LPData] Key: %s\n", $ts, $serv, $key);
        
        $srv = new LongPollServer($this, $key, $serv, $ts);
        $srv->run();
    }

    public function getResourcesFolder(){
        return 'src/resources/';
    }
    
    public function loadPlugins(){
        $loader = new PluginLoader;
        $parser = new BotInfoPluginParser;
        $mainDir = 'plugins'.DIRECTORY_SEPARATOR;
        
        if(!is_dir($mainDir)){
            mkdir($mainDir);
        }
        
        foreach(scandir($mainDir) as $plugin){
            if($plugin !== '.' && $plugin !== '..'){
                $info = $mainDir.$plugin.DIRECTORY_SEPARATOR.'plugininfo.yml';
                $content = file_get_contents($info);
                if($content === false){
                    continue;
                }

                if(($pluginData = $parser->parse($plugin, $content)) !== null){
                    $loader->putPlugin($pluginData, $plugin);
                }
            }
        }
        
        $loader->enable();
    }
    
    public function about(){
        $text = '
╔╗╔╗╔══╗╔══╗╔╗──╔╗╔═══╗╔╗──╔═══╗╔═══╗╔╗╔╗╔══╗─╔╗──╔══╗╔══╗╔══╗─╔══╗╔════╗
║║║║║╔═╝╚╗╔╝║║──║║║╔═╗║║║──║╔══╝║╔═╗║║║║║║╔╗║─║║──╚╗╔╝║╔═╝║╔╗║─║╔╗║╚═╗╔═╝
║╚╝║║╚═╗─║║─║╚╗╔╝║║╚═╝║║║──║╚══╗║╚═╝║║║║║║╚╝╚╗║║───║║─║║──║╚╝╚╗║║║║──║║
╚═╗║╚═╗║─║║─║╔╗╔╗║║╔══╝║║──║╔══╝║╔══╝║║║║║╔═╗║║║───║║─║║──║╔═╗║║║║║──║║
─╔╝║╔═╝║╔╝╚╗║║╚╝║║║║───║╚═╗║╚══╗║║───║╚╝║║╚═╝║║╚═╗╔╝╚╗║╚═╗║╚═╝║║╚╝║──║║
─╚═╝╚══╝╚══╝╚╝──╚╝╚╝───╚══╝╚═══╝╚╝───╚══╝╚═══╝╚══╝╚══╝╚══╝╚═══╝╚══╝──╚╝            
                '.PHP_EOL.PHP_EOL;
        $text .= 'An engine for simple group bot created by YarkaDev'.PHP_EOL.'Version 1.1.1';
        
        echo $text.PHP_EOL.PHP_EOL.'[Engine] Starting the bot'.PHP_EOL;
    }
}

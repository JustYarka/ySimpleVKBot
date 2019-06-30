<?php

namespace ru\yarka\example;

use simplebot\plugin\Plugin;
use simplebot\event\user\IncomingMessageEvent;
use simplebot\event\user\OutcomingMessageEvent;

class Main extends Plugin{
	
    public function onEnable() {
        echo "onEnable Main\n";
    }

    public function onLoad() {
        echo "onLoad Main\n";
    }

    public function handle(IncomingMessageEvent $e){
		$u = $e->getUser();
        echo 'Message from id ', $u->getId(), ' >> ', $e->getMessage(), "\n";
        $u->sendMessage('OK');
    }
    
    public function handleOut(OutcomingMessageEvent $e){
        $e->cancel(); //you can cancel some events
        echo $e->getMessage(), "\n";
        var_dump($e->cancelled());
    }
}
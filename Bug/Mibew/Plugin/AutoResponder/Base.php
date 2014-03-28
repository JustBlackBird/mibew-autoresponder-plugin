<?php
namespace Bug\Mibew\Plugin\AutoResponder;
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Base{
    private $thread;

    public function __construct(\Mibew\Thread $thread){
        $this->thread = $thread;
    }
    public function sendResponce($message){
        $lmsg = strtolower($message);
        switch($this->checkKeywords($lmsg)){
        case 1:
            return $this->thread->postMessage(
                \Mibew\Thread::KIND_INFO,
                'hello '.$this->thread->userName.'.'
            );
        case 2:
            return $this->thread->postMessage(
                \Mibew\Thread::KIND_INFO,
                'Just wait for a while. An operator will answer you soon.'
            );
        }
    }
    private function checkKeywords($message){
        $bad_chars = array("\r","\n","\0","\b",'.',',','@','#','-','_','+','=','(',')','*','?','/','\\','[',']','|','"','\'');
        $message = preg_replace('@\b@ui',' ',str_replace($bad_chars,'',$message));
        $list = explode(' ',$message);
        foreach($list as $w){
            if($w=='hi' || $w=='hello') return 1;
            else if($w=='there') return 2;
        }
        return 0;
    }
}

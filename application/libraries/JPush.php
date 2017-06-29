<?php  
defined('BASEPATH') OR exit('No direct script access allowed');  
  
class JPush{  
          
    private $app_key = '09c4cc50b66689da70084287';  
    private $master_secret = '6a94cdd8a4717c212c62d1d8';  
    private $push;  
  
    public function __construct() {  
        require_once("JPush/Client.php");//极光SDK包中的文件  
        $this->push = new JPush($this->app_key, $this->master_secret);  
    }  
  
    /**  
     * 单个推送  
     */  
    public function single_push($content, $uid, $type, $id) {  
        $result = $this->push->push()  
            // ->setPlatform(array('ios', 'android'))  
            ->setPlatform('android')  
            ->setOptions(null, null, null, true)  
            ->addAlias("{$uid}")  
            // ->addIosNotification($content, 'Voicemail.caf', 1, null, null, array('type'=>$type, 'id'=>$id))  
            ->addAndroidNotification($content, '', 1, array("type"=>$type, 'id'=>$id))  
            ->send();  
        return $result;  
    }  
  
    /**  
     * 多个推送  
     */  
    public function group_push($content, $type, $id) {  
        $result = $this->push->push()  
            ->setPlatform('android')  
            ->setOptions(null, null, null, true)  
            ->addAllAudience()  
            // ->addIosNotification($content, 'Voicemail.caf', 1, null, null, array('type'=>$type, 'id'=>$id))  
            ->addAndroidNotification($content, '', 1, array("type"=>$type, 'id'=>$id))  
            ->send();  
        return $result;  
    }  
}  
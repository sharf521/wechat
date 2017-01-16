<?php
namespace App\Controller;

use App\Model\SubSite;
use System\Lib\Controller as BaseController;
use System\Lib\DB;

class Controller extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $host = strtolower($_SERVER['HTTP_HOST']);
        if (strpos($host, '.wechat.') === false) {
            $this->is_wap = false;
            $this->template = 'default';
        } else {
            $this->is_wap = true;
            $this->template = 'default_wap';
        }

        $agent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if(strpos($agent, 'MicroMessenger') === false && strpos($agent, 'Windows Phone') === false)
        {
            $this->is_inWeChat=false;
            //echo '非微信浏览器不能访问';
            //die('Sorry！非微信浏览器不能访问');
        }else{
            $this->is_inWeChat=true;
        }
        if(!$this->is_wap){
            $this->site=(new SubSite())->where("domain like '%{$host}|%'")->first();
            if(!$this->site->is_exist){
                echo 'The site was not found！';
                exit;
            }
        }
    }
}
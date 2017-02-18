<?php

//控制器的父类

namespace App\Controller;

use App\Model\SubSite;
use App\Model\User;
use System\Lib\Controller as BaseController;

class Controller extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $host = strtolower($_SERVER['HTTP_HOST']);
        if (strpos($host, '.wechat.') === false) {
            $this->is_wap = false;
        } else {
            $this->is_wap = true;
        }
        $agent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if(strpos($agent, 'MicroMessenger') === false && strpos($agent, 'Windows Phone') === false)
        {
            $this->is_inWeChat=false;
            //die('Sorry！非微信浏览器不能访问');
        }else{
            $this->is_inWeChat=true;
        }
        if($this->is_wap){
            $this->site=(new SubSite())->find(1);
        }else{
            $this->site=(new SubSite())->where("domain like '%{$host}|%'")->first();
        }
        if(!$this->site->is_exist){
            echo 'The site was not found！';
            exit;
        }
    }
    
    public function check_login()
    {
        if($this->control !='login' && $this->control !='logout'){
            if(empty($this->user_id)){
                $url=urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
                if($this->is_inWeChat && $this->is_wap && false){
                    redirect("/wxOpen/oauth/?url={$url}");
                }else{
                    redirect("/user/login/?url={$url}");
                }
            }
            $this->user=(new User())->findOrFail($this->user_id);
            if(trim($this->user->headimgurl)==''){
                $this->user->headimgurl='/themes/member/images/no-img.jpg';
            }
        }
    }
}
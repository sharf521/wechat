<?php

//控制器的父类

namespace App\Controller;

use App\Helper;
use App\Model\SubSite;
use App\Model\User;
use System\Lib\Controller as BaseController;

class Controller extends BaseController
{
    protected $user_id;
    protected $username;
    public function __construct()
    {
        parent::__construct();
        $this->user_id = session('user_id');
        $this->username = session('username');
        $this->user_typeid = session('usertype');

        $host = strtolower($_SERVER['HTTP_HOST']);
        if (strpos($host, 'wap.') === false) {
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
        $this->site=(new SubSite())->where("domain like '%{$host}|%'")->orderBy('id')->first();
        if($this->site->is_exist){
            $arr_domain=explode('|',$this->site->domain);
            $this->site->pc_url='http://'.$arr_domain[0];
            $this->site->wap_url='http://'.$arr_domain[1];
            $this->site->goodsCates=unserialize($this->site->goodsCates);
            $this->site->articleCates=unserialize($this->site->articleCates);
        }else{
            echo 'The site was not found！';
            exit;
        }
        $this->st_uid=0;
        if(isset($_GET['st_uid'])){
            $st_uid=(int)$_GET['st_uid'];
            if($st_uid!=0){
                $this->st_uid=$st_uid;
                if($this->is_wap){
                    $this->store_url=Helper::getStoreUrl($st_uid,1);
                }else{
                    $this->store_url=Helper::getStoreUrl($st_uid,0);
                }
            }
        }
        if($this->st_uid==0){
            $this->home_url='/';
        }else{
            $this->home_url=$this->store_url;
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
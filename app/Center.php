<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/14
 * Time: 15:40
 */

namespace App;


use App\Model\System;

class Center
{
    private $url='';
    private $appid='';
    private $appsecret='';
    public function __construct()
    {
        $system=new System();
        $this->url=trim($system->getCode('pay_url'),'/');
        $this->appid=$system->getCode('pay_appid');
        $this->appsecret=$system->getCode('pay_appsecret');
    }

    public function loginUrl()
    {
        ///?appid=shop&redirect_uri=http://www.yuantuwang.com/ation/autho&sign=F5A720117F1A50D1281147ADF8BAF48E
//        if($redirect_uri==null){
//            $redirect_uri=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
//        }
        session()->set('target_url',$_SERVER["HTTP_REFERER"]);
        $data=array(
            'appid'=>$this->appid,
            'redirect_uri'=>'http://'.$_SERVER['HTTP_HOST'].'/user/auth'
        );
        $url="auth/login/?appid={$data['appid']}&redirect_uri={$data['redirect_uri']}&sign={$this->getSign($data)}";
        return $url;
    }

    public function registerUrl()
    {
        session()->set('target_url',$_SERVER["HTTP_REFERER"]);
        $data=array(
            'appid'=>$this->appid,
            'redirect_uri'=>'http://'.$_SERVER['HTTP_HOST'].'/user/auth'
        );
        $url="auth/register/?appid={$data['appid']}&redirect_uri={$data['redirect_uri']}&sign={$this->getSign($data)}";
        return $url;
    }

    public function getUserInfo($openid)
    {
        $params=array(
            'appid'=>$this->appid,
            'openid'=>$openid,
            'time'=>time()
        );
        $params['sign']=$this->getSign($params);
        $data['data']=json_encode($params);
        $html=$this->curl_url('user/info',$data);
        return json_decode($html);
    }

    public function getSign($data)
    {
        if(isset($data['sign'])){
            unset($data['sign']);
        }
        if (isset($data['data'])) {
            foreach ($data['data'] as $i => $v) {
                if (is_array($v)) {
                    ksort($data['data'][$i]);
                }
            }
        }
        ksort($data);
        $jsonStr = json_encode($data);
        $str = strtoupper(md5($jsonStr.$this->appsecret));
        return $str;
    }

    public function curl_url($url, $data = array())
    {
        $url=$this->url.'/'.$url;
        $ssl = substr($url, 0, 8) == "https://" ? TRUE : FALSE;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if ($data) {
            if (is_array($data)) {
                curl_setopt($ch, CURLOPT_POST, 1);
            } else {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($data))
                );
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        if ($ssl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
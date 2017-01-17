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
        $this->url=trim($system->getCode('center_url'),'/');
        $this->appid=$system->getCode('center_appid');
        $this->appsecret=$system->getCode('center_appsecret');
    }

    public function loginUrl($redirect_uri='')
    {
        ///?appid=shop&redirect_uri=http://www.yuantuwang.com/ation/autho&sign=F5A720117F1A50D1281147ADF8BAF48E
        if(empty($redirect_uri)){
            $target_url=$_SERVER["HTTP_REFERER"];//$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
        }else{
            $target_url=$redirect_uri;
        }
        session()->set('target_url',$target_url);
        $data=array(
            'appid'=>$this->appid,
            'redirect_uri'=>'http://'.$_SERVER['HTTP_HOST'].'/user/auth'
        );
        $url="auth/login/?appid={$data['appid']}&redirect_uri={$data['redirect_uri']}&sign={$this->getSign($data)}";
        return $url;
    }

    public function registerUrl($data=array())
    {
        session()->set('target_url',$_SERVER["HTTP_REFERER"]);
        $params=array(
            'appid'=>$this->appid,
            'redirect_uri'=>'http://'.$_SERVER['HTTP_HOST'].'/user/auth'
        );
        if(isset($data['r'])){
            $params['r']=$data['r'];
        }
        $url="auth/register/?appid={$params['appid']}&redirect_uri={$params['redirect_uri']}&sign={$this->getSign($params)}";
        if(isset($data['r'])){
            $url.="&r={$data['r']}";
        }
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
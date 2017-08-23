<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/14
 * Time: 15:40
 */

namespace App;


use App\Model\CenterPayOrder;
use App\Model\System;

class Center
{
    private $url='';
    public $appid='';
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

    //验证支付密码
    public function checkPayPwd($openid,$pay_password)
    {
        $params=array(
            'appid'=>$this->appid,
            'openid'=>$openid,
            'pay_password'=>$pay_password,
            'time'=>time()
        );
        $params['sign']=$this->getSign($params);
        $data['data']=json_encode($params);
        $html=$this->curl_url('user/checkPayPwd',$data);
        $json=json_decode($html);
        if($json->return_code=='success'){
            return true;
        }else{
            return false;
        }
    }

    //用户资金
    public function getUserFunc($openid)
    {
        $params=array(
            'appid'=>$this->appid,
            'openid'=>$openid,
            'time'=>time()
        );
        $params['sign']=$this->getSign($params);
        $data['data']=json_encode($params);
        $html=$this->curl_url('user/fund',$data);
        return json_decode($html);
    }
    
    
    public function rebateAdd($openid,$typeid=1,$integral=0,$remark='',$site_id=0)
    {
        $params=array(
            'appid'=>$this->appid,
            'openid'=>$openid,
            'typeid'=>(int)$typeid,
            'money'=>$integral,
            'time'=>time(),
            'remark'=>$remark,
            'site_id'=>$site_id
        );
        $params['sign']=$this->getSign($params);
        $data['data']=json_encode($params);
        $html=$this->curl_url('algorithm/rebate_add',$data);
        $json=json_decode($html);
        if($json->return_code=='success'){
            return true;
        }else{
            return $json->return_msg;
        }
    }

    //获取用户中心订单NO
    public function getFirstOrNewPayNo($data)
    {
        $params=array(
            'appid'=>$this->appid,
            'time'=>time(),
            'order_sn'=>$data['order_sn'],
            'order_pc_url'=>$data['order_pc_url'],
            'order_wap_url'=>$data['order_wap_url'],
            'openid'=>$data['openid'],
            'title'=>$data['title'],
            'money'=>$data['money'],
            'typeid'=>$data['typeid'],
            'remark'=>$data['remark'],
            'label'=>$data['label'],
            'other_nickname'=>$data['other_nickname'],
            'other_openid'=>$data['other_openid']
        );
        $params['sign']=$this->getSign($params);
        $data['data']=json_encode($params);
        $html=$this->curl_url('order/firstOrNew',$data);
        $json=json_decode($html);
        if($json->return_code=='success'){
            return $json->pay_no;
        }else{
            $error= $json->return_msg;
            redirect()->back()->with('error',$error);
        }
    }

    public function receivables($data)
    {
        $params=array(
            'appid'=>$this->appid,
            'time'=>time(),
            'order_no'=>time().rand(10000,99999),
            'openid'=>$data['openid'],
            'user_id'=>0,
            'body'=>$data['body'],
            'type'=>$data['type'],
            'remark'=>$data['remark'],
            'label'=>$data['label'],
            'data'=>array()
        );
        foreach ($data['data'] as $v){
            array_push($params['data'],$v);
        }
/*        $params=array(
            'appid'=>$this->appid,
            'time'=>time(),
            'order_no'=>time().rand(10000,99999),
            'openid'=>'',
            'user_id'=>'',
            'body'=>'test body',
            'type'=>1,
            'remark'=>'test remark',
            'label'=>'label',
            'data'=>array(
                array(
                    'openid'=>'7902435305879e82d91147355395698',
                    'type'=>1,
                    'remark'=>'收入',
                    'funds_available'=>10,
                    'integral_available'=>100,
                    'funds_available_now'=>46678.52,
                    'integral_available_now'=>2000
                )
            )
        );*/

        $params['sign']=$this->getSign($params);
        $data['data']=json_encode($params);
        $html=$this->curl_url('user/receivables',$data);
        $json=json_decode($html);
        if($json->return_code=='success'){
            $order=new CenterPayOrder();
            $order->order_no=$params['order_no'];
            $order->openid=$params['openid'];
            $order->body=$params['body'];
            $order->type=$params['type'];
            $order->remark=$params['remark'];
            $order->label=$params['label'];
            $order->data=serialize($params['data']);
            $order->pay_no=$json->pay_no;
            $order->save();
            return true;
        }else{
            return $json->return_msg;
        }
    }

    private function getSign($data)
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

    private function curl_url($url, $data = array())
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
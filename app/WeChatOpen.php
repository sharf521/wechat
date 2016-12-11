<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/2
 * Time: 11:58
 */

namespace App;

use App\Model\WeChatAuth;
use App\Model\WeChatTicket;
use EasyWeChat\Foundation\Application;
class WeChatOpen
{
    public $app;
    public $options;
    public function __construct()
    {
        $this->options = [
            'debug' => true,
            'app_id' => app('System')->getCode('appid'),
            'secret' => app('System')->getCode('appsecret'),
            'token' => app('System')->getCode('token'),
            'aes_key' => app('System')->getCode('aes_key'),
            'log' => [
                'level' => 'debug',
                'file' => ROOT.'/public/data/easywechatopen.log', // XXX: 绝对路径！！！！
            ],
            'oauth' => [
                'scopes'   => ['snsapi_userinfo'],
                'callback' => 'http://'.$_SERVER['HTTP_HOST'].'/index.php/wxOpen/oauth_callback',
            ],
            'guzzle' => [
                'timeout' => 4.0, // 超时时间（秒）
                // 'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
            ],
            // payment
            'payment' => [
                'merchant_id'        => '1373665102',
                'key'                => 'kfjakdfjakldsfjkasdq1234123411as',
                'cert_path'          => '/etc/ca/weixin/apiclient_cert.pem', // XXX: 绝对路径！！！！
                'key_path'           => '/etc/ca/weixin/apiclient_cert.p12',      // XXX: 绝对路径！！！！
                'notify_url'         => '',       // 你也可以在下单时单独设置来想覆盖它
                // 'device_info'     => '013467007045764',
                // 'sub_app_id'      => '',
                // 'sub_merchant_id' => '',
                // ...
            ],
        ];
        $this->app=new Application($this->options);
    }

    public function curl_url($url, $data = array())
    {
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
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if ($ssl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function log($msg,$file='log')
    {
        $file_path = ROOT . "/public/data/";
        if (!is_dir($file_path)) {
            mkdir($file_path, 0777, true);
        }
        $filename = $file_path . date("Ym") . "{$file}.log";
        $fp = fopen($filename, "a+");
        $time = date('Y-m-d H:i:s');
        $file = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
        $str = "time:{$time} \r\n{$file}\r\n{ $msg}\r\n\r\n";
        fputs($fp, $str);
        fclose($fp);
    }

    public function getOpenid()
    {
        $host_arr=explode('.',$_SERVER['HTTP_HOST']);
        $appid=$host_arr[0];
        $component_appid=$this->options['app_id'];
        //通过code获得openid
        if (!isset($_GET['code'])){
            //触发微信返回code码
            $redirect_uri = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING']);
            $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state=STATE&component_appid={$component_appid}#wechat_redirect";
            redirect($url);
            exit();
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $url="https://api.weixin.qq.com/sns/oauth2/component/access_token?appid={$appid}&code={$code}&grant_type=authorization_code&component_appid={$component_appid}&component_access_token={$this->getComponentAccessToken()}";
            $html=$this->curl_url($url);
            $json=json_decode($html);
            return $json->openid;
        }
    }

    public function getPreAuthCode()
    {
        $url="https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token={$this->getComponentAccessToken()}";
        $arr=array("component_appid"=>$this->options['app_id']);
        $html=$this->curl_url($url,json_encode($arr));
        $json=json_decode($html);
        if(isset($json->pre_auth_code)){
            return $json->pre_auth_code;
        }else{
            echo $html;
            exit;
        }
    }
    public function getComponentAccessToken()
    {
        $chatTicket = (new WeChatTicket())->first();
        if ($chatTicket->token_expires_in < time()){
            $arr = array(
                'component_appid' => $this->options['app_id'],
                'component_appsecret' => $this->options['secret'],
                'component_verify_ticket' => $chatTicket->ComponentVerifyTicket
            );
            $html = $this->curl_url('https://api.weixin.qq.com/cgi-bin/component/api_component_token', json_encode($arr));
            $this->log('https://api.weixin.qq.com/cgi-bin/component/api_component_token' . json_encode($arr), 'token');
            $this->log("内容" . $html, 'token');
            $html = json_decode($html);
            $chatTicket->component_access_token = $html->component_access_token;
            $chatTicket->token_expires_in = time() + 4000;
            $chatTicket->save();
        }
        return $chatTicket->component_access_token;
    }

    public function getAccessToken($app_id)
    {
        $auth = (new WeChatAuth())->findOrFail($app_id);
        if ($auth->authorizer_expires_in < time()) {
            $url = "https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token={$this->getComponentAccessToken()}";
            $arr = array(
                'component_appid' => $this->options['app_id'],
                'authorizer_appid' => $auth->authorizer_appid,
                'authorizer_refresh_token' => $auth->authorizer_refresh_token
            );
            $this->log($url . json_encode($arr), 'token');
            $html = $this->curl_url($url, json_encode($arr));
            $this->log("内容2" . $html, 'token');
            $json = json_decode($html);
            if (isset($json->authorizer_access_token)) {
                $auth->authorizer_access_token = $json->authorizer_access_token;
                $auth->authorizer_refresh_token = $json->authorizer_refresh_token;
                $auth->authorizer_expires_in = time() + 7000;
                $auth->save();
            } else {
                echo $html;
                $this->log("ERROR:" . $html, 'token');
            }
        }
        return $auth->authorizer_access_token;
    }

    public function getPayParams($prepay_id)
    {
        $pay=array();
        $pay['appId']=$this->options['app_id'];
        $time=time();
        $pay['timeStamp']="$time";
        $pay['nonceStr']=$this->getNonceStr();
        $pay['package']="prepay_id={$prepay_id}";
        $pay['signType']='MD5';
        $sign=$this->MakeSign($pay);
        $arr=array(
            'timestamp'=>$pay['timeStamp'],
            'nonceStr'=>$pay['nonceStr'],
            'package'=>$pay['package'],
            'signType'=>$pay['signType'],
            'paySign'=>$sign
        );
        return $arr;
    }

    /**
     *
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return 产生的随机字符串
     */
    private function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }
    /**
     * 格式化参数格式化成url参数
     */
    private function ToUrlParams($values)
    {
        $buff = "";
        foreach ($values as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 生成签名
     */
    private function MakeSign($values)
    {
        //签名步骤一：按字典序排序参数
        ksort($values);
        $string = $this->ToUrlParams($values);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$this->options['payment']['key'];
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 生成二维码
     */
    public function qrcode($txt,$forever=false)
    {
        $qrcode = $this->app->qrcode;
        if($forever){
            $result=$qrcode->forever($txt);//永久二维码
        }else{
            $result = $qrcode->temporary($txt, 6 * 24 * 3600);
        }
        $ticket = $result->ticket;// 或者 $result['ticket']
        $url = $qrcode->url($ticket);
        return $url;
    }

    /* 短地址*/
    public function shorten($uri)
    {
        $url = $this->app->url;
        $shortUrl=$url->shorten($uri);
        return $shortUrl->short_url;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/1
 * Time: 18:07
 */

namespace App\Controller;

use App\Model\WeChatAuth;
use App\Model\WeChatTicket;
use App\WeChatOpen;
use EasyWeChat\Message\Text;
use System\Lib\Request;

class WxOpenController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->weChat=new WeChatOpen();
        $this->app=$this->weChat->app;
        $this->component_appid=$this->weChat->options['app_id'];
        $this->component_appsecret=$this->weChat->options['secret'];
    }

    public function index()
    {
        $redirect_uri='http://'.$_SERVER['HTTP_HOST'].url('wxOpen/auth_code');
        $code=$this->getPreAuthCode();
        $url="https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid={$this->component_appid}&pre_auth_code={$code}&redirect_uri={$redirect_uri}";
        echo $url;
        echo "<a href='{$url}'>授权</a>";
    }

    //授权返回
    public function auth_code(Request $request)
    {
        //?auth_code=queryauthcode@@@9QJDTmdBO731Nz9_I-DyLgb-EOygA8WedAmM_h4LaXSxebJODjNYAWRVL9x-OKRzEOQQGSAzkOAaB5vkd-Po9A&expires_in=3600
        $auth_code=$request->get('auth_code');
        $ticket=(new WeChatTicket())->first();
        $url="https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token={$ticket->component_access_token}";
        $arr=array(
            'component_appid'=>$this->component_appid,
            'authorization_code'=>$auth_code
        );
        $html=$this->weChat->curl_url($url,json_encode($arr));
        $json=json_decode($html);
        if(isset($json->authorization_info)){
            $json=$json->authorization_info;
            $auth=(new WeChatAuth())->find($json->authorizer_appid);
            $auth->user_id=$this->user_id;
            $auth->auth_code=$auth_code;
            $auth->expires_in=time()+3000;
            $auth->authorizer_appid=$json->authorizer_appid;
            $auth->authorizer_access_token=$json->authorizer_access_token;
            $auth->authorizer_refresh_token=$json->authorizer_refresh_token;
            $auth->authorizer_expires_in=time()+7000;
            $auth->func_info=serialize($json->func_info);
            $auth->save();
        }else{
            echo $html;
        }
        $this->log($html,'auth_code');
        echo 'ok';
    }

    //wxOpen/event/wx02560f146a566747
    public function event(Request $request)
    {
        $app_id=$request->get(2);
        $this->app['access_token']->setToken($this->getAccessToken($app_id));

        //$auth=(new WeChatAuth())->findOrFail($app_id);
        //$this->app['access_token']->setToken($auth->authorizer_access_token);

        $server=$this->weChat->app->server;
        $server->setMessageHandler(function ($message) {
            switch ($message->MsgType) {
                case 'event':
                    return new Text(['content' => $message->Event.'from_callback']);
                    //return $this->event($message);
                    break;
                case 'text':
                    return $this->text($message);
                    break;
                default:
                    # code...
                    break;
            }
        });
        $server->serve()->send();

        $msg=$server->getMessage();
        $msg=json_encode($msg);
        $this->log($msg,'event');
    }

    private function text($message)
    {
        if($message->Content=='TESTCOMPONENT_MSG_TYPE_TEXT'){
            return new Text(['content' => 'TESTCOMPONENT_MSG_TYPE_TEXT_callback']);
        }
        if($message->Content=='abcd'){
            $staff = $this->app->staff; // 客服管理
            $_message=new Text(['content' =>'1234']);
            $staff->message($_message)->to($message->FromUserName)->send();
        }
        if(substr($message->Content,0,16)=='QUERY_AUTH_CODE:'){
            $query_auth_code=substr($message->Content,16);
            $redirect_uri='http://'.$_SERVER['HTTP_HOST'].url("wxOpen/auth_code/?auth_code={$query_auth_code}");
            $this->log("\r\n AAA".$redirect_uri,'event');
            $html=$this->weChat->curl_url($redirect_uri);
            $this->log($html,'event');

            $str=$query_auth_code."_from_api";
            //发送消息
            $staff = $this->app->staff; // 客服管理
            $_message=new Text(['content' =>$str]);
            $staff->message($_message)->to($message->FromUserName)->send();
        }
    }

    //平台接收消息
    public function ticket(WeChatTicket $chatTicket,Request $request)
    {
        $server=$this->weChat->app->server;
        $msg=$server->getMessage();
        $this->log(json_encode($msg),'ticket');
        //10分钟推送一次
        if($msg['InfoType']=='component_verify_ticket'){
            $chatTicket=$chatTicket->first();
            $chatTicket->timestamp=$request->timestamp;
            $chatTicket->nonce=$request->nonce;
            $chatTicket->encrypt_type=$request->encrypt_type;
            $chatTicket->msg_signature=$request->msg_signature;
            $chatTicket->CreateTime=$msg['CreateTime'];
            $chatTicket->InfoType=$msg['InfoType'];
            $chatTicket->ComponentVerifyTicket=$msg['ComponentVerifyTicket'];
            $chatTicket->save();
            //token
            if($chatTicket->token_expires_in<time()){
                $arr=array(
                    'component_appid'=>$this->component_appid,
                    'component_appsecret'=>$this->component_appsecret,
                    'component_verify_ticket'=>$chatTicket->ComponentVerifyTicket
                );
                $html=$this->weChat->curl_url('https://api.weixin.qq.com/cgi-bin/component/api_component_token',json_encode($arr));
                $html=json_decode($html);
                $chatTicket->component_access_token=$html->component_access_token;
                $chatTicket->token_expires_in=time()+5000;
                $chatTicket->save();
            }
        }elseif($msg['InfoType']=='authorized'){
            $AuthorizationCode=$msg['AuthorizationCode'];
            $redirect_uri='http://'.$_SERVER['HTTP_HOST'].url("wxOpen/auth_code/?auth_code={$AuthorizationCode}");
            $this->log($redirect_uri);
            $html=$this->weChat->curl_url($redirect_uri);
            $this->log("BBB".$html,'ticket');
        }
        echo 'success';
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

    private function getPreAuthCode()
    {
        $chatTicket=(new WeChatTicket())->first();
        $url="https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token={$chatTicket->component_access_token}";
        $arr=array("component_appid"=>$this->component_appid);
        $html=$this->weChat->curl_url($url,json_encode($arr));
        $json=json_decode($html);
        if(isset($json->pre_auth_code)){
            return $json->pre_auth_code;
        }else{
            echo $html;
            exit;
        }
    }

    private function getComponentAccessToken()
    {
        $chatTicket=(new WeChatTicket())->first();
        $arr=array(
            'component_appid'=>$this->component_appid,
            'component_appsecret'=>$this->component_appsecret,
            'component_verify_ticket'=>$chatTicket->ComponentVerifyTicket
        );
        $html=$this->weChat->curl_url('https://api.weixin.qq.com/cgi-bin/component/api_component_token',json_encode($arr));
        $this->log('https://api.weixin.qq.com/cgi-bin/component/api_component_token'.json_encode($arr),'token');
        $this->log("内容".$html,'token');
        $html=json_decode($html);
        $chatTicket->component_access_token=$html->component_access_token;
        $chatTicket->token_expires_in=time()+5000;
        $chatTicket->save();
        return $chatTicket->component_access_token;
    }

    private function getAccessToken($app_id)
    {
        $auth=(new WeChatAuth())->findOrFail($app_id);
        if($auth->authorizer_expires_in >=time() && false){
            return $auth->authorizer_access_token;
        }else{
            $chatTicket=(new WeChatTicket())->first();
            $component_access_token= $chatTicket->component_access_token;
            //$component_access_token=$this->getComponentAccessToken();

            $url="https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token={$component_access_token}";
            $arr=array(
                'component_appid'=>$this->component_appid,
                'authorizer_appid'=>$auth->authorizer_appid,
                'authorizer_refresh_token'=>$auth->authorizer_refresh_token
            );
            $this->log($url.json_encode($arr),'token');
            $html=$this->weChat->curl_url($url,json_encode($arr));
            $this->log("内容2".$html,'token');
            $json=json_decode($html);
            if(isset($json->authorizer_access_token)){
                $auth->authorizer_access_token=$json->authorizer_access_token;
                $auth->authorizer_refresh_token=$json->authorizer_refresh_token;
                $auth->authorizer_expires_in=time()+7000;
                $auth->save();
            }else{
                echo $html;
            }
            return $auth->authorizer_access_token;
        }
    }
}
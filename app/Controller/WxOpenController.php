<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/1
 * Time: 18:07
 */

namespace App\Controller;

use App\Model\User;
use App\Model\UserWx;
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

    //公众号授权返回
    public function auth_code(Request $request)
    {
        //?auth_code=queryauthcode@@@9QJDTmdBO731Nz9_I-DyLgb-EOygA8WedAmM_h4LaXSxebJODjNYAWRVL9x-OKRzEOQQGSAzkOAaB5vkd-Po9A&expires_in=3600
        $auth_code=$request->get('auth_code');
        $url="https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token={$this->getComponentAccessToken()}";
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
        if($message->Content=='shop'){
            //$return="http://{$message->ToUserName}.{$_SERVER['HTTP_HOST']}/member";
            //$return=$this->weChat->shorten($return);
            $return='http://wx02560f146a566747.wechat.yuantuwang.com/member';
            return new Text(['content' => $return]);
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

    //用户授权
    public function oauth(Request $request)
    {
        $url=$request->get('url');
        //没有登陆时去授权
        if ($this->user_id=='' || $this->user_id==0) {
            session()->set('target_url',$url);

            $host_arr=explode('.',$_SERVER['HTTP_HOST']);
            $appid=$host_arr[0];
            $redirect_uri='http://'.$_SERVER['HTTP_HOST'].url("wxOpen/oauth_callback/");
            $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state=STATE&component_appid={$this->component_appid}#wechat_redirect";
            redirect($url);
            exit;
        }else{
            redirect($url);
        }
    }
    public function oauth_callback(Request $request)
    {
        $code=$request->get('code');
        $appid=$request->get('appid');
        $openid=$this->getWebOpenId($appid,$code);

        echo $openid;
        exit;

        $userServer=$this->app->user;
        $userInfo=$userServer->get($openid);
        $user_wx=(new UserWx())->where("unionid=?")->bindValues($userInfo->unionid)->first();
        $user_wx->subscribe = $userInfo->subscribe;
        $user_wx->openid = $userInfo->openid;
        $user_wx->nickname = addslashes($userInfo->nickname);
        $user_wx->sex = $userInfo->sex;
        $user_wx->city = $userInfo->city;
        $user_wx->country = $userInfo->country;
        $user_wx->province = $userInfo->province;
        $user_wx->language = $userInfo->language;
        $user_wx->headimgurl = $userInfo->headimgurl;
        $user_wx->subscribe_time = $userInfo->subscribe_time;
        $user_wx->unionid = $userInfo->unionid;
        $user_wx->remark = $userInfo->remark;
        $user_wx->groupid = $userInfo->groupid;
        $user_wx->tagid_list =json_encode($userInfo->tagid_list);
        $user_wx->save();

        $user=(new User())->where("unionid=?")->bindValues($userInfo->unionid)->first();
        $user->unionid=$userInfo->unionid;
        $user->headimgurl=$userInfo->headimgurl;
        $user->nickname=addslashes($userInfo->nickname);
        if(intval($user->type_id)==0){
            $user->type_id=1;
        }
        $user->save();
        $result=$user->login(array('direct'=>1, 'unionid'=>$user->unionid));
        if($result===true){
            $target_url=session('target_url');
            redirect($target_url); // 跳转
        }else{
            echo $result;
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
        $url="https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token={$this->getComponentAccessToken()}";
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
        $chatTicket = (new WeChatTicket())->first();
        if ($chatTicket->token_expires_in < time()){
            $arr = array(
                'component_appid' => $this->component_appid,
                'component_appsecret' => $this->component_appsecret,
                'component_verify_ticket' => $chatTicket->ComponentVerifyTicket
            );
            $html = $this->weChat->curl_url('https://api.weixin.qq.com/cgi-bin/component/api_component_token', json_encode($arr));
            $this->log('https://api.weixin.qq.com/cgi-bin/component/api_component_token' . json_encode($arr), 'token');
            $this->log("内容" . $html, 'token');
            $html = json_decode($html);
            $chatTicket->component_access_token = $html->component_access_token;
            $chatTicket->token_expires_in = time() + 4000;
            $chatTicket->save();
        }
        return $chatTicket->component_access_token;
    }

    private function getAccessToken($app_id)
    {
        $auth = (new WeChatAuth())->findOrFail($app_id);
        if ($auth->authorizer_expires_in < time()) {
            $url = "https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token={$this->getComponentAccessToken()}";
            $arr = array(
                'component_appid' => $this->component_appid,
                'authorizer_appid' => $auth->authorizer_appid,
                'authorizer_refresh_token' => $auth->authorizer_refresh_token
            );
            $this->log($url . json_encode($arr), 'token');
            $html = $this->weChat->curl_url($url, json_encode($arr));
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

    private function getWebOpenId($appid,$code)
    {
        $auth = (new WeChatAuth())->findOrFail($appid);
        if($auth->web_expires_in < time()){
            echo '<<<<<<';
            $url="https://api.weixin.qq.com/sns/oauth2/component/access_token?appid={$appid}&code={$code}&grant_type=authorization_code&component_appid={$this->component_appid}&component_access_token={$this->getComponentAccessToken()}";
            $html=$this->weChat->curl_url($url);
            $json=json_decode($html);
            if(isset($json->access_token)){
                $auth->web_access_token=$json->access_token;
                $auth->web_expires_in=$json->web_expires_in+6000;
                $auth->web_refresh_token=$json->refresh_token;
                $auth->save();
                return $json->openid;
            }else{
                echo $html;
                exit;
            }
        }/*else{
            $url="https://api.weixin.qq.com/sns/oauth2/component/access_token?appid={$appid}&code={$code}&grant_type=authorization_code&component_appid={$this->component_appid}&component_access_token={$this->getComponentAccessToken()}";
            $html=$this->weChat->curl_url($url);
            $json=json_decode($html);
            if(isset($json->access_token)){
                $auth->web_access_token=$json->access_token;
                $auth->web_expires_in=$json->web_expires_in+6000;
                $auth->web_refresh_token=$json->refresh_token;
                $auth->save();
                return $json->openid;
            }else{
                echo $html;
                exit;
            }
        }*/
        echo '>>>>';
    }
}
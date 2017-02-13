<?php

namespace App\Controller\Home;

use App\Controller\Controller;
use App\Model\Order;
use App\Model\User;
use App\Model\UserWx;
use App\Model\WeChatAuth;
use App\Model\WeChatTicket;
use App\WeChatOpen;
use EasyWeChat\Message\Text;
use System\Lib\Request;

class WxOpenController extends Controller
{
    private $weChat;
    private $app;
    private $component_appid;
    private $component_appsecret;
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
        $code=$this->weChat->getPreAuthCode();
        $url="https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid={$this->component_appid}&pre_auth_code={$code}&redirect_uri={$redirect_uri}";
        echo "<a href='{$url}'>授权</a>";
    }

    //公众号授权返回
    public function auth_code(Request $request)
    {
        //?auth_code=queryauthcode@@@9QJDTmdBO731Nz9_I-DyLgb-EOygA8WedAmM_h4LaXSxebJODjNYAWRVL9x-OKRzEOQQGSAzkOAaB5vkd-Po9A&expires_in=3600
        $auth_code=$request->get('auth_code');
        $url="https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token={$this->weChat->getComponentAccessToken()}";
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
            //公众号帐号基本信息
            $url="https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token={$this->weChat->getComponentAccessToken()}";
            $arr=array(
                'component_appid'=>$this->component_appid,
                'authorizer_appid'=>$auth->authorizer_appid
            );
            $html=$this->weChat->curl_url($url,json_encode($arr));
            $json=json_decode($html);
            if(isset($json->authorizer_info)){
                $json=$json->authorizer_info;
                $auth->nick_name=addslashes($json->nick_name);
                $auth->head_img=$json->head_img;
                $auth->service_type_info=serialize($json->service_type_info);
                $auth->verify_type_info=serialize($json->verify_type_info);
                $auth->user_name=$json->user_name;
                $auth->principal_name=addslashes($json->principal_name);
                $auth->business_info=serialize($json->business_info);
            }else{
                echo $html;
            }
            $auth->save();
        }else{
            echo $html;
        }
        $this->weChat->log($html,'auth_code');
        echo 'ok';
    }

    //wxOpen/event/wx02560f146a566747
    public function event(Request $request)
    {
        $app_id=$request->get(2);
        $this->app['access_token']->setToken($this->weChat->getAccessToken($app_id));

        //$auth=(new WeChatAuth())->findOrFail($app_id);
        //$this->app['access_token']->setToken($auth->authorizer_access_token);

        $server=$this->weChat->app->server;
        $server->setMessageHandler(function ($message) {
            switch ($message->MsgType) {
                case 'event':
                    //return new Text(['content' => $message->Event.'from_callback']);
                    //return $this->event($message);
                    break;
                case 'text':
                    return $this->text($message);
                    break;
                default:
                    return true;
                    break;
            }
        });
        $server->serve()->send();

        $msg=$server->getMessage();
        $msg=json_encode($msg);
        $this->weChat->log($msg,'event');
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

            $appid=(new WeChatAuth())->where('user_name=?')->bindValues($message->ToUserName)->value('authorizer_appid');
            $return="http://{$appid}.{$_SERVER['HTTP_HOST']}/member";
            $return=$this->weChat->shorten($return);
            //$return='http://wx02560f146a566747.wechat.yuantuwang.com/member';
            return new Text(['content' => $return]);
        }
        if(substr($message->Content,0,16)=='QUERY_AUTH_CODE:'){
            $query_auth_code=substr($message->Content,16);
            $redirect_uri='http://'.$_SERVER['HTTP_HOST'].url("wxOpen/auth_code/?auth_code={$query_auth_code}");
            $this->weChat->log("\r\n AAA".$redirect_uri,'event');
            $html=$this->weChat->curl_url($redirect_uri);
            $this->weChat->log($html,'event');

            $str=$query_auth_code."_from_api";
            //发送消息
            $staff = $this->app->staff; // 客服管理
            $_message=new Text(['content' =>$str]);
            $staff->message($_message)->to($message->FromUserName)->send();
        }
        return true;
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
            $redirect_uri=urlencode('http://'.$_SERVER['HTTP_HOST'].url("wxOpen/oauth_callback/"));
            $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state=STATE&component_appid={$this->component_appid}#wechat_redirect";
            //echo $url;
            redirect($url);
            exit;
        }else{
            redirect($url);
        }
    }
    public function oauth_callback(Request $request)
    {
        var_dump($request);
        exit;
        $code=$request->get('code');
        $appid=$request->get('appid');
        $userInfo=$this->getWebUserInfo($appid,$code);
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
            echo $target_url;
            //redirect($target_url); // 跳转
        }else{
            echo $result;
        }
    }

    private function getWebUserInfo($appid,$code)
    {
        $url="https://api.weixin.qq.com/sns/oauth2/component/access_token?appid={$appid}&code={$code}&grant_type=authorization_code&component_appid={$this->component_appid}&component_access_token={$this->weChat->getComponentAccessToken()}";
        $html=$this->weChat->curl_url($url);
        $json=json_decode($html);
        if(isset($json->access_token)){
            $url="https://api.weixin.qq.com/sns/userinfo?access_token={$json->access_token}&openid={$json->openid}&lang=zh_CN";
            $html=$this->weChat->curl_url($url);
            $json=json_decode($html);
            if(isset($json->nickname)){
                return $json;
            }else{
                echo $html;
                exit;
            }
        }else{
            echo $html;
            exit;
        }
    }

    //平台接收消息
    public function ticket(WeChatTicket $chatTicket,Request $request)
    {
        $server=$this->weChat->app->server;
        $msg=$server->getMessage();
        $this->weChat->log(json_encode($msg),'ticket');
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
            $this->weChat->log($redirect_uri);
            $html=$this->weChat->curl_url($redirect_uri);
            $this->weChat->log("BBB".$html,'ticket');
        }
        echo 'success';
    }

    public function payNotify()
    {
        $response = $this->app->payment->handleNotify(function($notify, $successful){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $arr=explode('[#]',$notify->attach);
            $id=(int)$arr[0];
            $user_id=(int)$arr[1];
            $out_trade_no=$notify->out_trade_no;
            $order=new Order();
            $order =$order->find($id);
            if (!$order) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            // 如果订单存在
            // 检查订单是否已经更新过支付状态
//            if ($order->status!=3 || $order->out_trade_no !=$out_trade_no) {
//                return true; // 已经支付成功了就不再更新了
//            }
            // 用户是否支付成功
            if ($successful) {
                $order->payed_at = time();
                $order->payed_money=(float)math($notify->total_fee,100,'/',2);
                $order->status = 4;
                $order->save(); // 保存
/*                //消息start
                $notice = $this->app->notice;
                $templateId = 'tmGk3uxIeNke-tG7zBbHVzrxuHI_zB_cdKm69ZWfmm4';
                $url = "http://{$_SERVER['HTTP_HOST']}/index.php/weixin/orderShow/?task_id={$task->id}";
                $data = array(
                    "first"  => "您好，您的订单【{$task->print_type}】已付款成功！",
                    "keyword1"   => $out_trade_no,
                    "keyword2"  => date('Y-m-d H:i'),
                    "keyword3"  => $task->paymoney,
                    "keyword4"  => '微信支付',
                    "remark" => "感谢您的惠顾。",
                );
                $openid=$task->User()->openid;
                $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($openid)->send();
                //消息end*/
            } else {
                // 用户支付失败
            }
            return true; // 返回处理完成
        });
        $response->send();
    }


}
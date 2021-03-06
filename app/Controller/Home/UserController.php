<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/14
 * Time: 17:22
 */

namespace App\Controller\Home;


use App\Center;
use App\Controller\Controller;
use App\Model\User;
use App\WeChatOpen;
use System\Lib\Request;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login(Request $request)
    {
        $center=new Center();
        $url=$center->loginUrl($request->get('url',false),$request->get('r'));
        if($this->is_wap){
            $url=$this->site->center_url_wap.'/'.$url;
        }else{
            $url=$this->site->center_url.'/'.$url;
        }
        redirect($url);
    }

    public function invite(Request $request)
    {
        $center=new Center();
        $url=$center->registerUrl(array('r'=>$request->get('r')));
        if($this->is_wap){
            $url=$this->site->center_url_wap.'/'.$url;
        }else{
            $url=$this->site->center_url.'/'.$url;
        }
        redirect($url);
    }

    public function register()
    {
        $center=new Center();
        $url=$center->registerUrl();
        if($this->is_wap){
            $url=$this->site->center_url_wap.'/'.$url;
        }else{
            $url=$this->site->center_url.'/'.$url;
        }
        redirect($url);
    }

    //去充值
    public function goWeChatPay(Request $request)
    {

        $url="{$this->site->center_url_wap}/member/account/recharge";
        redirect($url);
        return;
        $money=(float)$request->get('money');
        $url=urlencode($request->get('url'));
        if(! $this->is_inWeChat){
            echo ' 仅限微信内调用！';
            exit;
        }
        $this->check_login();
        $wechat_openid=$this->user->wechat_openid;
        if(empty($wechat_openid)){
            $get_wechat_openid = $request->get('wechat_openid');
            if(empty($get_wechat_openid)){
                $this_url='http://'.$_SERVER['HTTP_HOST'].urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
                $url = "http://wx02560f146a566747.wechat.yuantuwang.com/user/getWeChatOpenId/?url={$this_url}";
                redirect($url);
            }else{
                $wechat_openid=$get_wechat_openid;
                $user=(new User())->find($this->user_id);
                $user->wechat_openid=$wechat_openid;
                $user->save();
            }
        }
        $center=new Center();
        $url_param="&money={$money}&url={$url}&appid={$center->appid}&openid={$this->user->openid}";
        $url="http://centerwap.yuantuwang.com/wechat/recharge/?wechat_openid={$wechat_openid}".$url_param;
        redirect($url);
    }

    public function auth(Request $request,Center $center,User $user)
    {
        $getUrl=$request->get('url',false);
        if(empty($getUrl)){
            $target_url=session('target_url');
            if(empty($target_url)){
                $target_url='/';
            }
        }else{
            $target_url=$getUrl;
        }
        $openid=$request->get('openid');
        $uInfo=$center->getUserInfo($openid);
        /**
         *   public 'openid' => string '765332031587dc0ca98f69109379520' (length=31)
        public 'username' => string 'admin11' (length=7)
        public 'headimgurl' => string 'http://center.test.cn:800/themes/member/images/no-img.jpg' (length=57)
        public 'nickname' => null
        public 'qq' => null
        public 'tel' => null
        public 'address' => null
        public 'invite_openid' => string '7902435305879e82d91147355395698' (length=31)
        public 'email' => string 'qiaoshaof@163.com' (length=17)
        public 'return_code' => string 'success' (length=7)
         */
        if($uInfo->return_code=='success'){
            $user=$user->where("openid='{$openid}'")->first();
            if(!$user->is_exist){
                $user->site_id=$this->site->id;
                $user->openid=$openid;
                $user->type_id=1;
                if($uInfo->invite_openid!=''){
                    $invite=(new User())->where("openid='{$uInfo->invite_openid}'")->first();
                    if($invite->is_exist){
                        $user->invite_userid=$invite->id;
                        $user->invite_path=$invite->invite_path.$invite->id.',';
                        //更新邀请人的邀请数量
                        $invite->invite_count=$invite->invite_count+1;
                        $invite->save();
                    }
                }
            }
            $user->username=$uInfo->username;
            $user->headimgurl=$uInfo->headimgurl;
            $user->nickname=$uInfo->nickname;
            $user->email=$uInfo->email;
            $user->qq=$uInfo->qq;
            $user->name=$uInfo->name;
            $user->save();
            $user->login(array('direct'=>1,'openid'=>$openid));
            redirect($target_url);
        }else{
            echo 'openid error !';
            exit;
        }
    }
}
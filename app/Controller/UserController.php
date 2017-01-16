<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/14
 * Time: 17:22
 */

namespace App\Controller;


use App\Center;
use App\Model\User;
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
        $url=$center->loginUrl($request->get('url'));
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
        $url='http://center.test.cn:800/'.$url;
        redirect($url);
    }

    public function auth(Request $request,Center $center,User $user)
    {
        $target_url=session('target_url');
        if(empty($target_url)){
            $target_url='/';
        }
        $openid=$request->get('openid');
        $uInfo=$center->getUserInfo($openid);
        if($uInfo->return_code=='success'){
            $user=$user->where("openid='{$openid}'")->first();
            if(!$user->is_exist){
                $user->openid=$openid;
                $user->type_id=1;
            }
            $user->username=$uInfo->username;
            $user->headimgurl=$uInfo->headimgurl;
            $user->nickname=$uInfo->nickname;
            $user->email=$uInfo->email;
            $user->save();
            $user->login(array('direct'=>1,'openid'=>$openid));
            redirect($target_url);
        }else{
            echo 'openid error !';
            exit;
        }
    }
}
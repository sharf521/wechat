<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/17
 * Time: 14:14
 */

namespace App\Controller\Member;


use App\Helper;
use App\Model\User;
use App\WeChat;
use App\WeChatOpen;

class InviteController extends MemberController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(User $user)
    {
        $data['invite_url']='http://'.$_SERVER['HTTP_HOST']."/user/invite?r={$this->username}";
        $invite_img_url=$this->site->wap_url."/user/invite/?r={$this->username}";
        $data['invite_img']=Helper::QRcode($invite_img_url,"invite_wap_{$this->site->id}",$this->user_id);
        $result=$user->where("invite_userid=?")->bindValues($this->user_id)->get();
        $data['result']=$result;
        if($this->is_inWeChat){
            $data['invite_url']=(new WeChat())->shorten($invite_img_url);
        }
        $this->view('invite',$data);
    }
}
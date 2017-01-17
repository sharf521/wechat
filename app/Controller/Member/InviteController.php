<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/17
 * Time: 14:14
 */

namespace App\Controller\Member;


use App\Model\User;

class InviteController extends MemberController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(User $user)
    {
        $user_id=$this->user_id;

        $file_dir = ROOT . "/public/data/upload/".ceil($user_id/2000)."/".$user_id."/";
        if (!is_dir($file_dir)) {
            mkdir($file_dir, 0777, true);
        }
        $file_path=$file_dir.'invite.png';
        $data['invite_url']='http://'.$_SERVER['HTTP_HOST']."/user/invite?r={$this->username}";
        \PHPQRCode\QRcode::png($data['invite_url'],$file_path, 'L', 4, 2);

        $data['invite_img']="/data/upload/".ceil($user_id/2000)."/".$user_id."/invite.png";
        $result=$user->where("invite_userid=?")->bindValues($this->user_id)->get();
        $data['result']=$result;
        $this->view('invite',$data);
    }
}
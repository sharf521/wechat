<?php
namespace App\Controller\Member;

use App\Controller\Controller;

class MemberController extends Controller
{
    protected $user;
    protected $is_inWeChat=false;
    public function __construct()
    {
        parent::__construct();
        $this->check_login();
        if($this->is_wap){
            $this->template = 'member_wap';
        }else{
            $this->template = 'member';
        }
    }

    public function error()
    {
        echo 'not find page';
    }
}
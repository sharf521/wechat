<?php
namespace App\Controller\Member;

use App\Model\User;
use System\Lib\Controller as BaseController;
use System\Lib\DB;

class MemberController extends BaseController
{
    protected $user;
    protected $is_inWeChat=false;
    public function __construct()
    {
        parent::__construct();
        $agent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if(strpos($agent, 'MicroMessenger') === false && strpos($agent, 'Windows Phone') === false)
        {
            //echo '非微信浏览器不能访问';
            //die('Sorry！非微信浏览器不能访问');
        }else{
            $this->is_inWeChat=true;
        }
        $host = strtolower($_SERVER['HTTP_HOST']);
/*        $this->site=DB::table('subsite')->where("domain like '%{$host}|%'")->row();
        if(empty($this->site)){
            echo 'The site was not found！';
            exit;
        }*/
        if (strpos($host, 'wap.') === false) {
            $this->is_wap = false;
            $this->template = 'member';
        } else {
            $this->is_wap = true;
            $this->template = 'member_wap';
        }
        $this->is_wap = true;
        $this->template = 'member_wap';
        if($this->control !='login' && $this->control !='logout'){
            if(empty($this->user_id)){
                $url=urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
                //redirect("/login?url={$url}");
                redirect("/wxOpen/oauth/?url={$url}");
                exit;
            }
        }
        $this->user=(new User())->findOrFail($this->user_id);
        if(trim($this->user->headimgurl)==''){
            $this->user->headimgurl='/themes/member/images/no-img.jpg';
        }
    }

    public function error()
    {
        echo 'not find page';
    }
}
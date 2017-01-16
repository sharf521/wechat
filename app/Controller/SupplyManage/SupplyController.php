<?php
namespace App\Controller\SupplyManage;

use App\Controller\Controller;
use App\Model\User;

class SupplyController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if($this->is_wap){
            $this->template = 'supply_wap';
        }else{
            $this->template = 'supply';
        }
        if($this->control !='login' && $this->control !='logout'){
            if(empty($this->user_id)){
                $url=urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
                if($this->is_inWeChat){
                    redirect("/wxOpen/oauth/?url={$url}");
                }else{
                    redirect(url("/user/login/?url={$url}"));
                }
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
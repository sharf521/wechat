<?php
namespace App\Controller\SellManage;
use App\Controller\Controller;
use App\Model\Shipping;
use App\Model\ShopCategory;
use App\Model\User;

class SellController extends Controller
{
    protected $user;
    public function __construct()
    {
        parent::__construct();
        if($this->is_wap){
            $this->template = 'sell_wap';
        }else{
            $this->template = 'sell';
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

    public function getCates()
    {
        return (new ShopCategory())->getListTree($this->user_id);
        //return (new ShopCategory())->where("user_id=?")->bindValues($this->user_id)->get();
    }

    public function getShippings()
    {
        return (new Shipping())->where('user_id=?')->bindValues($this->user_id)->get();
    }

    public function error()
    {
        echo 'not find page';
    }
}
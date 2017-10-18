<?php
namespace App\Controller\Member;

use App\Model\AppUser;
use App\Model\Order;
use App\Model\User;

class IndexController extends MemberController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        //待付款
        $data['buyer_status1_count']=(new Order())->where('buyer_id=? and status=1')->bindValues($this->user_id)->value('count(id)');
        $data['buyer_status4_count']=(new Order())->where('buyer_id=? and status=4')->bindValues($this->user_id)->value('count(id)');

        if($this->user->is_shop){
            //待发货
            $data['seller_status3_count']=(new Order())->where('seller_id=? and status=3')->bindValues($this->user_id)->value('count(id)');
        }
        if($this->user->is_supply){
            //待发货
            $data['supplyer_status3_count']=(new Order())->where('supply_user_id=? and status=3')->bindValues($this->user_id)->value('count(id)');
        }
        $this->title='个人中心';
        $this->view('manage', $data);
    }

    public function logout(User $user)
    {
        $user->logout();
        redirect('/');
        exit;
    }
}
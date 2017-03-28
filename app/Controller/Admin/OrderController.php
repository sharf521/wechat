<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/10
 * Time: 11:45
 */

namespace App\Controller\Admin;

use App\Model\Order;
use System\Lib\Request;

class OrderController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(Order $order,Request $request)
    {
        $where = " status>-1";
        $buyer_id=(int)$request->get('buyer_id');
        $seller_id=(int)$request->get('seller_id');
        $supply_user_id=(int)$request->get('supply_user_id');
        $starttime=$request->get('starttime');
        $endtime=$request->get('endtime');
        if ($buyer_id!=0) {
            $where .= " and buyer_id={$buyer_id}";
        }
        if ($seller_id!=0) {
            $where .= " and seller_id={$seller_id}";
        }
        if($supply_user_id!=0){
            $where .= " and supply_user_id={$supply_user_id}";
        }
        if(!empty($starttime)){
            $where.=" and created_at>=".strtotime($starttime);
        }
        if(!empty($endtime)){
            $where.=" and created_at<".strtotime($endtime);
        }
        $data['result']=$order->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $this->view('order',$data);
    }
}
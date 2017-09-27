<?php

namespace App\Controller\Member;


use App\Center;
use App\Model\CashierLog;
use App\Model\Order;
use App\Model\PreSaleOrder;
use System\Lib\DB;
use System\Lib\Request;

class PreSaleOrderController extends MemberController
{
    public function __construct()
    {
        parent::__construct();
        $this->title='我的订单';
    }

    public function index(PreSaleOrder $order,Request $request)
    {
        $data['orders']=$order->where('buyer_id=?')->bindValues($this->user_id)->orderBy('id desc')->pager($request->get('page'),5);
        $this->view('preSaleOrder',$data);
    }
    
    //去支付中心支付定金
    public function prePay(PreSaleOrder $order,Request $request)
    {
        $order=$order->where('order_sn=?')->bindValues($request->get('sn'))->first();
        if(!$order->is_exist){
            redirect()->back()->with('error','订单不存在！');
        }
        if($order->buyer_id!=$this->user_id){
            redirect()->back()->with('error','异常');
        }
        if($order->status!=1){
            redirect()->back()->with('error','异常，请勿重复支付！');
        }
        $center=new Center();
        $cashierLog=(new CashierLog())->where("typeid='preSaleOrder_pre' and order_id='{$order->id}'")->first();
        if(!$cashierLog->is_exist){
            $cashier_no='CH'.time().rand(10000,99999);
            $cashierLog->cashier_no=$cashier_no;
            $cashierLog->order_id=$order->id;
            $cashierLog->typeid='preSaleOrder_pre';
            $cashierLog->user_id=$order->buyer_id;
            $cashierLog->title=$order->goods_name;
            $params=array(
                'order_sn'=>$cashier_no,
                'order_pc_url'=>$this->site->pc_url,
                'order_wap_url'=>$this->site->wap_url,
                'openid'=>$this->user->openid,
                'title'=>$cashierLog->title,
                'money'=>$order->pre_money,
                'typeid'=>'preSaleOrder_pre',
                'label'=>"preSaleOrder:{$order->id}",
                'remark'=>''
            );
            $cashierLog->out_trade_no=$center->getOrNewCashierNo($params);
            $cashierLog->save();
        }
        $url=($this->is_wap)?$this->site->center_url_wap:$this->site->center_url;
        $url.="/".$center->cashierUrl($cashierLog->out_trade_no);
        redirect($url);
    }

    //去支付中心支付尾款
    public function endPay(PreSaleOrder $order,Request $request)
    {
        $order=$order->where('order_sn=?')->bindValues($request->get('sn'))->first();
        if(!$order->is_exist){
            redirect()->back()->with('error','订单不存在！');
        }
        if($order->buyer_id!=$this->user_id){
            redirect()->back()->with('error','异常');
        }
        if($order->status!=3){
            redirect()->back()->with('error','异常，请勿重复支付！');
        }
        $center=new Center();
        $cashierLog=(new CashierLog())->where("typeid='preSaleOrder_end' and order_id='{$order->id}'")->first();
        if(!$cashierLog->is_exist){
            $cashier_no='CH'.time().rand(10000,99999);
            $cashierLog->cashier_no=$cashier_no;
            $cashierLog->order_id=$order->id;
            $cashierLog->typeid='preSaleOrder_end';
            $cashierLog->user_id=$order->buyer_id;
            $cashierLog->title=$order->goods_name;
            $params=array(
                'order_sn'=>$cashier_no,
                'order_pc_url'=>$this->site->pc_url,
                'order_wap_url'=>$this->site->wap_url,
                'openid'=>$this->user->openid,
                'title'=>$cashierLog->title,
                'money'=>math($order->order_money,$order->pre_money,'-',2),
                'typeid'=>'preSaleOrder_end',
                'label'=>"preSaleOrder:{$order->id}",
                'remark'=>''
            );
            $cashierLog->out_trade_no=$center->getOrNewCashierNo($params);;
            $cashierLog->save();
        }
        $url=($this->is_wap)?$this->site->center_url_wap:$this->site->center_url;
        $url.="/".$center->cashierUrl($cashierLog->out_trade_no);
        redirect($url);
    }
    
    public function success(Order $order,Request $request)
    {
        $id=$request->get('id');
        $user_id=$this->user_id;
        $order=$order->findOrFail($id);
        if($order->buyer_id!=$user_id){
            redirect()->back()->with('error','异常');
        }
        if($order->status!=4){
            redirect()->back()->with('error','异常，请勿重复确认收货！');
        }
        if($_POST){
            $center=new Center();
            $checkPwd=$center->checkPayPwd($this->user->openid,$request->post('zf_password'));
            if($checkPwd!==true){
                redirect()->back()->with('error','支付密码错误！');
            }
            try {
                DB::beginTransaction();
                $order->success($this->user->openid);
                $order->updateOrderGoodsStatus(5);
                DB::commit();
                redirect("order")->with('msg','操作完成');
            } catch (\Exception $e) {
                DB::rollBack();
                $error= "Failed: " . $e->getMessage();
                redirect()->back()->with('error',$error);
            }
        }else{
            $data['order']=$order;
            $data['shipping']=$order->OrderShipping();
            $data['goods']=$order->OrderGoods();
            $data['shop']=$order->Shop();
            $this->title='确认收货';
            $this->view('order_success',$data);
        }
    }
}
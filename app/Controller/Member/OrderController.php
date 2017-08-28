<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/23
 * Time: 14:52
 */

namespace App\Controller\Member;


use App\Center;
use App\Model\Order;
use App\Model\OrderGoods;
use App\Model\System;
use App\Model\User;
use App\WeChat;
use App\WeChatOpen;
use System\Lib\DB;
use System\Lib\Request;

class OrderController extends MemberController
{
    public function __construct()
    {
        parent::__construct();
        $this->title='我的订单';
    }

    public function index(Order $order,Request $request)
    {
        $data['orders']=$order->where('buyer_id=?')->bindValues($this->user_id)->orderBy('id desc')->pager($request->get('page'),5);
        $this->view('order',$data);
    }

    //待付款
    public function status1(Order $order,Request $request)
    {
        $data['orders']=$order->where('buyer_id=? and status=1')->bindValues($this->user_id)->orderBy('id desc')->pager($request->get('page'));
        $this->view('order',$data);
    }

    //待发货
    public function status3(Order $order,Request $request)
    {
        $data['orders']=$order->where('buyer_id=? and status=3')->bindValues($this->user_id)->orderBy("id desc")->pager($request->get('page'));
        $this->view('order',$data);
    }

    //待收货
    public function status4(Order $order,Request $request)
    {
        $data['orders']=$order->where('buyer_id=? and status=4')->bindValues($this->user_id)->orderBy('id desc')->pager($request->get('page'));
        $this->view('order',$data);
    }
    
    //去支付中心支付
    public function centerPay(Order $order,Request $request)
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
        $trade_no=$order->out_trade_no;
        $center=new Center();
        if(empty($trade_no)){
            $orderGoods=$order->OrderGoods();
            if(count($orderGoods)==1){
                $orderTitle=$orderGoods[0]->goods_name;
            }else{
                $orderTitle=$orderGoods[0]->goods_name.' 等多件。';
            }
            $params=array(
                'order_sn'=>$order->order_sn,
                'order_pc_url'=>$this->site->pc_url,
                'order_wap_url'=>$this->site->wap_url,
                'openid'=>$this->user->openid,
                'title'=>$orderTitle,
                'money'=>$order->order_money,
                'typeid'=>'order_pay',
                'remark'=>''
            );
/*            $sell_user=(new User())->find($order->seller_id);
            $params['other_nickname']=$sell_user->username;
            $params['other_openid']=$sell_user->openid;*/
            $trade_no=$center->getOrNewCashierNo($params);
            $order->out_trade_no=$trade_no;
            $order->save();
        }
        $url=($this->is_wap)?$this->site->center_url_wap:$this->site->center_url;
        $url.="/".$center->cashierUrl($trade_no);
        redirect($url);
    }

    public function pay(Order $order,Request $request,System $system)
    {
        $id=$request->get('id');
        $user_id=$this->user_id;
        $order=$order->findOrFail($id);
        if($order->buyer_id!=$user_id){
            redirect()->back()->with('error','异常');
        }
        if($order->status!=1){
            redirect()->back()->with('error','异常，请勿重复支付！');
        }

        $convert_rate=(float)$system->getCode('convert_rate');
        if(empty($convert_rate)){
            $convert_rate=2.52;
        }
        $center=new Center();
        $account=$center->getUserFunc($this->user->openid);
        if($_POST){
            $checkPwd=$center->checkPayPwd($this->user->openid,$request->post('zf_password'));
            if($checkPwd!==true){
                redirect()->back()->with('error','支付密码错误！');
            }
            $integral=(float)$request->post('integral');
            if($integral<0){
                redirect()->back()->with('error','不能为负数！');
            }
            if($integral > $account->integral_available){
                redirect()->back()->with('error','可用积分不足！');
            }
            $_money=math($integral,$convert_rate,'/',3);
            $_money=round_money($_money,1,2);
            $money=math($order->order_money,$_money,'-',2);
            if($money > $account->funds_available){
                redirect()->back()->with('error','可用资金不足！');
            }
            try {
                DB::beginTransaction();
                $remark="订单号：{$order->order_sn}";
                $params=array(
                    'openid'=>$this->user->openid,
                    'body'=>'',
                    'type'=>'order_pay',
                    'remark'=>$remark,
                    'label'=>"order_sn:{$order->order_sn}",
                    'data'=>array(
                        array(
                            'openid'=>$this->user->openid,
                            'type'=>'order_pay',
                            'remark'=>$remark,
                            'funds_available' =>'-'.$money,
                            'integral_available' =>'-'.$integral,
                            'funds_available_now'=>$account->funds_available,
                            'integral_available_now'=>$account->integral_available,
                        )
                    )
                );
                $return=$center->receivables($params);
                if($return===true){
                    $order->payed_funds=$money;
                    $order->payed_integral=$integral;
                    $order->payed_at=time();
                    $order->status=3;
                    $order->save();
                    $order->updateOrderGoodsStatus(3);
                    DB::commit();
                    redirect("order")->with('msg','付款完成');
                }else{
                    throw new \Exception($return);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                $error= "Failed: " . $e->getMessage();
                redirect()->back()->with('error',$error);
            }
        }else{
            /*        if( $this->is_inWeChat){
                $openid=(new WeChatOpen())->getOpenid();
                $weChat=new WeChat();
                $app=$weChat->app;
                $payment = $app->payment;
                $attributes = [
                    'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
                    'body'             => '支付订单',
                    'out_trade_no'     => time().rand(10000,99999),
                    'total_fee'        => math($orderMoneys,100,'*',2),
                    'attach'=>$id.'[#]'.$this->user_id,
                    'openid'=>$openid,
                    'notify_url'       => "http://{$_SERVER['HTTP_HOST']}/index.php/wxOpen/payNotify/"
                ];
                $_order=new \EasyWeChat\Payment\Order($attributes);
                $result = $payment->prepare($_order);
                if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
                    $js = $app->js;
                    $data['config']=$js->config(array('chooseWXPay','openAddress','checkJsApi'), false);
                    $pay=$weChat->getPayParams($result->prepay_id);
                    $data['pay']=$pay;
                    $order->out_trade_no=$attributes['out_trade_no'];
                    $order->save();
                }
            }*/
            $this->title='支付订单';
            $data['order']=$order;
            $data['convert_rate']=$convert_rate;
            $data['account']=$account;
            $data['shipping']=$order->OrderShipping();
            $data['goods']=$order->OrderGoods();
            $data['shop']=$order->Shop();
            $this->view('order_pay',$data);
        }
    }
    public function cancel(Order $order,Request $request)
    {
        $user_id=$this->user_id;
        $id=$request->get('id');
        $order=$order->findOrFail($id);
        if($order->buyer_id!=$user_id){
            echo '异常';exit;
        }
        if($order->status!=1){
            redirect()->back()->with('msg','状态异常');
        }else{
            try{
                DB::beginTransaction();
                $order->cancel($this->user);
                $order->updateOrderGoodsStatus(2);
                DB::commit();
                redirect()->back()->with('msg','订单取消成功！');
            }catch (\Exception $e){
                $error=$e->getMessage();
                redirect()->back()->with('error',$error);
                DB::rollBack();
            }
        }
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
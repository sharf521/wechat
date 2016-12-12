<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/23
 * Time: 14:52
 */

namespace App\Controller\Member;


use App\Model\Order;
use App\Model\User;
use App\WeChat;
use App\WeChatOpen;
use System\Lib\Request;

class OrderController extends MemberController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Order $order,Request $request)
    {
        $data['orders']=$order->where('buyer_id=?')->bindValues($this->user_id)->orderBy('id desc')->pager($request->get('page'),2);
        $this->view('order',$data);
    }

    //待付款
    public function status1(Order $order,Request $request)
    {
        $data['orders']=$order->where('buyer_id=? and status=1')->bindValues($this->user_id)->orderBy('id desc')->pager($request->get('page'));
        $this->view('order',$data);
    }

    public function status3(Order $order,Request $request)
    {
        $data['orders']=$order->where('buyer_id=? and status=3')->bindValues($this->user_id)->orderBy("id desc")->pager($request->get('page'));
        $this->view('order',$data);
    }

    public function status4(Order $order,Request $request)
    {
        $data['orders']=$order->where('buyer_id=? and status=4')->bindValues($this->user_id)->orderBy('id desc')->pager($request->get('page'));
        $this->view('order',$data);
    }

    public function pay(Order $order,Request $request)
    {
        $user_id=$this->user_id;
        $id=$request->get('id');
        $order=$order->findOrFail($id);
        if($order->buyer_id!=$user_id){
            echo '异常';exit;
        }
        //$openid=(new User())->where('id=?')->bindValues($this->user_id)->value('unionid');
        $openid=(new WeChatOpen())->getOpenid();
        $weChat=new WeChat();
        $app=$weChat->app;
        $payment = $app->payment;
        $attributes = [
            'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
            'body'             => '支付订单',
            'out_trade_no'     => time().rand(10000,99999),
            'total_fee'        => math($order->order_money,100,'*',2),
            'attach'=>$id,
            'openid'=>$openid,
            'notify_url'       => "http://{$_SERVER['HTTP_HOST']}/index.php/wxOpen/payNotify/"
        ];
        $_order=new \EasyWeChat\Payment\Order($attributes);
        $result = $payment->prepare($_order);

        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
            $js = $app->js;
            $data['config']=$js->config(array('chooseWXPay','openAddress','checkJsApi'), true);
            $pay=$weChat->getPayParams($result->prepay_id);
            $data['pay']=$pay;
            $order->out_trade_no=$attributes['out_trade_no'];
            $order->save();
        }

        $data['title_herder']='支付中。。';

        $data['order']=$order;
        $data['orderGoods']=$order->OrderGoods();
        $data['shipping']=$order->OrderShipping();
        $this->view('order_pay',$data);
    }


}

//打印输出数组信息
function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
    }
}
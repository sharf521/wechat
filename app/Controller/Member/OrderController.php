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
        echo $id;
        $order=$order->findOrFail($id);
        if($order->buyer_id!=$user_id){
            echo '异常';exit;
        }
echo 222;
        $openid=(new User())->where('id=?')->bindValues($this->user_id)->value('unionid');
        $weChat=new WeChat();
        $app=$weChat->app;
echo 33;
        require_once ROOT."/wxpay/lib/WxPay.Api.php";
        require_once ROOT."/wxpay/example/WxPay.JsApiPay.php";
        echo 111;
        //①、获取用户openid
        $tools = new \JsApiPay();
        //$openId = $tools->GetOpenid();

        echo 222;

        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("test");
        $input->SetAttach("test");
        $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openid);
        $order = \WxPayApi::unifiedOrder($input);
        echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
        printf_info($order);
        $jsApiParameters = $tools->GetJsApiParameters($order);

        //获取共享收货地址js函数参数
        $editAddress = $tools->GetEditAddressParameters();

        //③、在支持成功回调通知中处理成功之后的事宜，见 notify.php

        $data['jsApiParameters']=$jsApiParameters;

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
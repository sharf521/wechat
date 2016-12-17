<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/23
 * Time: 14:52
 */

namespace App\Controller\SellManage;


use App\Model\Order;
use System\Lib\DB;
use System\Lib\Request;

class OrderController extends SellController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Order $order,Request $request)
    {
        $data['orders']=$order->where('seller_id=?')->bindValues($this->user_id)->orderBy('id desc')->pager($request->get('page'));
        $this->view('order',$data);
    }

    //待付款
    public function status1(Order $order,Request $request)
    {
        $data['orders']=$order->where('seller_id=? and status=1')->bindValues($this->user_id)->orderBy('id desc')->pager($request->get('page'));
        $this->view('order',$data);
    }
    //待发货
    public function status3(Order $order,Request $request)
    {
        $data['orders']=$order->where('seller_id=? and status=3')->bindValues($this->user_id)->orderBy("id desc")->pager($request->get('page'));
        $this->view('order',$data);
    }
    //待收货
    public function status4(Order $order,Request $request)
    {
        $data['orders']=$order->where('seller_id=? and status=4')->bindValues($this->user_id)->orderBy('id desc')->pager($request->get('page'));
        $this->view('order',$data);
    }

    public function cancel(Order $order,Request $request)
    {
        $user_id=$this->user_id;
        $id=$request->get('id');
        $order=$order->findOrFail($id);
        if($order->seller_id!=$user_id){
            echo '异常';exit;
        }
        if($order->status!=3){
            redirect()->back()->with('msg','状态异常');
        }else{
            try{
                DB::beginTransaction();
                $order->cancel($this->user_id);
                DB::commit();
                redirect()->back()->with('msg','订单取消成功！');
            }catch (\Exception $e){
                $error=$e->getMessage();
                redirect()->back()->with('error',$error);
                DB::rollBack();
            }
        }
    }
    //修改
    public function editMoney(Order $order,Request $request)
    {
        $user_id=$this->user_id;
        $id=$request->get('id');
        $order=$order->findOrFail($id);
        if($order->seller_id!=$user_id){
            echo '异常';exit;
        }
        if($order->status!=1){
            redirect()->back()->with('msg','状态异常');
        }
        if($_POST){
            $order->order_money=(float)$request->post('money');
            $order->save();
            redirect("order")->with('msg','操作成功');
        }else{
            $data['order']=$order;
            $this->view('order_edit',$data);
        }
    }
    
    public function editShipping(Order $order,Request $request)
    {
        $user_id=$this->user_id;
        $id=$request->get('id');
        $order=$order->findOrFail($id);
        if($order->seller_id!=$user_id){
            echo '异常';exit;
        }
        if($order->status!=3){
            redirect()->back()->with('msg','状态异常');
        }
        $shipping=$order->OrderShipping();
        if($_POST){
            $shipping->shipping_name=$request->post('shipping_name');
            $shipping->shipping_no=$request->post('shipping_no');
            $shipping->shipping_fee=$request->post('shipping_fee');
            $shipping->shopping_at=time();
            $shipping->save();
            $order->status=4;
            $order->shipping_at=time();
            $order->save();
            redirect("order")->with('msg','操作成功');
        }else{
            $data['order']=$order;
            $data['shipping']=$shipping;
            $this->view('order_edit',$data);
        }
    }

    public function show(Order $order,Request $request)
    {
        $user_id=$this->user_id;
        $id=$request->get('id');
        $order=$order->findOrFail($id);
        if($order->seller_id!=$user_id){
            echo '异常';exit;
        }
        $data['order']=$order;
        $data['shipping']=$order->OrderShipping();
        $this->view('order_show',$data);
    }

}
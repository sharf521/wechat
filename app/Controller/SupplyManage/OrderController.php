<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/23
 * Time: 14:52
 */

namespace App\Controller\SupplyManage;


use App\Model\Order;
use App\Model\SupplyGoods;
use System\Lib\DB;
use System\Lib\Request;
use System\Lib\Session;

class OrderController extends SupplyController
{
    public function __construct()
    {
        parent::__construct();
        $this->title='订单管理';
    }

    public function index(Order $order,Request $request)
    {
        $data['orders']=$order->where('supply_user_id=?')->bindValues($this->user_id)->orderBy('id desc')->pager($request->get('page'));
        $this->view('order',$data);
    }

    //待付款
    public function status1(Order $order,Request $request)
    {
        $data['orders']=$order->where('supply_user_id=? and status=1')->bindValues($this->user_id)->orderBy('id desc')->pager($request->get('page'));
        $this->view('order',$data);
    }
    //待发货
    public function status3(Order $order,Request $request)
    {
        $data['orders']=$order->where('supply_user_id=? and status=3')->bindValues($this->user_id)->orderBy("id desc")->pager($request->get('page'));
        $this->view('order',$data);
    }
    //待收货
    public function status4(Order $order,Request $request)
    {
        $data['orders']=$order->where('supply_user_id=? and status=4')->bindValues($this->user_id)->orderBy('id desc')->pager($request->get('page'));
        $this->view('order',$data);
    }

    //修改
    public function editMoney(Order $order,Request $request)
    {
        $user_id=$this->user_id;
        $id=$request->get('id');
        $order=$order->findOrFail($id);
        if($order->supply_user_id!=$user_id){
            echo '异常';exit;
        }
        if($order->status!=1){
            redirect()->back()->with('msg','状态异常');
        }
        if($_POST){
            $order->shipping_fee=abs((float)$request->post('shipping_fee'));
            $order->order_money=math($order->goods_money,$order->shipping_fee,'+',2);
            $order->save();
            if($this->is_wap){
                redirect("order")->with('msg','操作成功');
            }else{
                (new Session())->flash('msg','操作成功');
                echo '<script>window.parent.location.reload();</script>';
                exit;
            }
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
        if($order->supply_user_id!=$user_id){
            echo '异常';exit;
        }
        if($order->status!=3){
            redirect()->back()->with('msg','状态异常');
        }
        $shipping=$order->OrderShipping();
        if($_POST){
            $shipping->shipping_name=$request->post('shipping_name');
            $shipping->shipping_no=$request->post('shipping_no');
            $shipping->shipping_fee=(float)$request->post('shipping_fee');
            $shipping->shipping_at=time();
            $shipping->save();
            $order->status=4;
            $order->shipping_at=time();
            $order->save();
            if($this->is_wap){
                redirect("order")->with('msg','操作成功');
            }else{
                (new Session())->flash('msg','操作成功');
                echo '<script>window.parent.location.reload();</script>';
                exit;
            }
        }else{
            $data['order']=$order;
            $data['shipping']=$shipping;
            $this->view('order_edit',$data);
        }
    }

}
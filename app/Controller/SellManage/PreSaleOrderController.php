<?php
namespace App\Controller\SellManage;

use App\Model\PreSaleOrder;
use System\Lib\DB;
use System\Lib\Request;
use System\Lib\Session;

class PreSaleOrderController extends SellController
{
    public function __construct()
    {
        parent::__construct();
        $this->title='我的预订';
    }

    public function index(PreSaleOrder $order,Request $request)
    {
        $data['orders']=$order->where('seller_id=?')->bindValues($this->user_id)->orderBy('id desc')->pager($request->get('page'));
        $this->view('preSaleOrder',$data);
    }

    public function setPreTrue(PreSaleOrder $order,Request $request)
    {
        $user_id=$this->user_id;
        $id=$request->get('id');
        $order=$order->findOrFail($id);
        if($order->seller_id!=$user_id){
            echo '异常';exit;
        }
        if($order->status!=2){
            redirect()->back()->with('msg','状态异常');
        }
        $order->status=3;
        $order->save();
        redirect()->back()->with('msg','操作成功');
    }
    
    public function editShipping(PreSaleOrder $order,Request $request)
    {
        $user_id=$this->user_id;
        $id=$request->get('id');
        $order=$order->findOrFail($id);
        if($order->seller_id!=$user_id){
            echo '异常';exit;
        }
        if($order->status!=4){
            redirect()->back()->with('msg','状态异常');
        }
        $shipping=$order->OrderShipping();
        if($_POST){
            $shipping->shipping_name=$request->post('shipping_name');
            $shipping->shipping_no=$request->post('shipping_no');
            $shipping->shipping_fee=(float)$request->post('shipping_fee');
            $shipping->shipping_at=time();
            $shipping->save();
            $order->status=5;
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
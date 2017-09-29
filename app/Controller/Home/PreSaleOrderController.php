<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/25
 * Time: 15:38
 */

namespace App\Controller\Home;


use App\Model\Goods;
use App\Model\OrderShipping;
use App\Model\PreSaleOrder;
use App\Model\UserAddress;
use System\Lib\DB;
use System\Lib\Request;

class PreSaleOrderController extends HomeController
{
    public function __construct()
    {
        parent::__construct();
        $this->check_login();
    }

    public function detail(PreSaleOrder $order,Request $request)
    {
        $sn=$request->get('sn');
        $user_id=$this->user_id;
        $order=$order->where('order_sn=?')->bindValues($sn)->first();
        if(!$order->is_exist){
            redirect()->back()->with('error','订单不存在！');
        }
        if($this->user->type_id==1){
            if($order->buyer_id!=$user_id && $order->seller_id!=$user_id){
                redirect()->back()->with('error','异常');
            }
        }
        $this->title='预订单详情';
        $data['order']=$order;
        $data['shipping']=$order->OrderShipping();
        $data['shop']=$order->Shop();
        $data['buyer']=$order->Buyer();
        $this->view('preSale_detail',$data);
    }

    //确认订单
    public function confirm(Request $request,UserAddress $address)
    {
        $user_id=$this->user_id;
        $goods_id=(int)$request->get('goods_id');
        $quantity=(int)$request->get('quantity');
        $spec_id=(int)$request->get('spec_id');
        $address_id=(int)$request->address_id;
        $goods=(new Goods())->find($goods_id);
        if(!($goods->is_exist && $goods->is_presale)){
            redirect()->back()->with('error','异常');
        }
        if($address_id==0){
            $address=$address->where('user_id=? and is_default=1')->bindValues($this->user_id)->first();
        }else{
            $address=$address->where('user_id=? and id='.$address_id)->bindValues($this->user_id)->first();
        }
        $goods=$goods->addSpec($spec_id);

        $order=new PreSaleOrder();
        $order_sn='PRE'.time().rand(10000,99999);
        $order->site_id=$this->site->id;
        $order->order_sn=$order_sn;
        $order->buyer_id=$user_id;
        $order->seller_id=$goods->user_id;
        $order->buyer_remark=$request->post('buyer_remark');
        $order->spec_id=$spec_id;
        $order->quantity=$quantity;
        $order->goods_id=$goods->id;
        $order->goods_name=$goods->name;
        $order->goods_image=$goods->image_url;
        $order->price=$goods->price;
        $order->spec_1=$goods->spec_1;
        $order->spec_2=$goods->spec_2;
        $order->order_money=math($order->price,$order->quantity,'*',2);
        //if($this->user->is_shop){
            $order->pre_money=math($goods->pre_price,$order->quantity,'*',2);
            $order->status=1;
        //}else{
         //   $order->pre_money=0;
         //   $order->status=2;
       // }
        if($_POST){
            if(!$address->is_exist){
                redirect()->back()->with('error','请填写收货地址！');
            }
            try{
                DB::beginTransaction(); 

                if($goods->stock_count==0){
                    throw  new \Exception("己卖完了！");
                }
                if($quantity > $goods->stock_count){
                    throw  new \Exception("库存不足，仅剩{$goods->stock_count}件！");
                }
                //减少库存
                $goods->setStockCount(-$quantity,$spec_id);
                $order->save();
                //收货人
                $shipping=new OrderShipping();
                $shipping->order_sn=$order_sn;
                $shipping->name=$address->name;
                $shipping->phone=$address->phone;
                $shipping->region_name=$address->region_name;
                $shipping->address=$address->address;
                $shipping->zipcode=$address->zipcode;
                $shipping->save();
                
                DB::commit();
            }catch(\Exception $e){
                $error=$e->getMessage();
                redirect()->back()->with('error',$error);
                DB::rollBack();
            }
            redirect("/member/preSaleOrder/?st_uid={$this->st_uid}")->with('msg','提交成功，请支付！');
        }else{
            $data['goods']=$goods;
            $data['order']=$order;
            $data['address']=$address;
            $data['addressList']=$address->where('user_id=?')->bindValues($this->user_id)->get();
            $this->view('preSale_order',$data);
        }
    }
}
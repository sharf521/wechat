<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/25
 * Time: 15:38
 */

namespace App\Controller\Home;


use App\Model\Cart;
use App\Model\Order;
use App\Model\OrderGoods;
use App\Model\OrderShipping;
use App\Model\UserAddress;
use System\Lib\DB;
use System\Lib\Request;

class OrderController extends HomeController
{
    public function __construct()
    {
        parent::__construct();
        $this->check_login();
    }

    public function detail(Order $order,Request $request)
    {
        $sn=$request->get('sn');
        $user_id=$this->user_id;
        $order=$order->where('order_sn=?')->bindValues($sn)->first();
        if(!$order->is_exist){
            redirect()->back()->with('error','订单不存在！');
        }
        if($this->user->type_id==1){
            if($order->buyer_id!=$user_id && $order->seller_id!=$user_id && $order->supply_user_id!=$user_id){
                redirect()->back()->with('error','异常');
            }
        }
        $this->title='订单详情';
        $data['order']=$order;
        $data['shipping']=$order->OrderShipping();
        $data['goods']=$order->OrderGoods();
        $data['shop']=$order->Shop();
        $data['buyer']=$order->Buyer();
        $data['supplyer']=$order->Supply();
        $this->view('order_detail',$data);
    }

    //确认订单
    public function confirm(Request $request,Cart $cart,UserAddress $address)
    {
        $user_id=$this->user_id;
        $cart_id=$request->cart_id;//array[]
        $address_id=(int)$request->address_id;
        if(empty($cart_id)){
            redirect()->back()->with('error','至少选择一件商品');
        }
        if($address_id==0){
            $address=$address->where('user_id=? and is_default=1')->bindValues($this->user_id)->first();
        }else{
            $address=$address->where('user_id=? and id='.$address_id)->bindValues($this->user_id)->first();
        }
        $arr=array(
            'buyer_id'=>$this->user_id,
            'cart_id'=>$cart_id
        );
        if($_POST){
            if(!$address->is_exist){
                redirect()->back()->with('error','请填写收货地址！');
            }
            $arr_area=explode('-',$address->region_name);
            $arr['cityName']=$arr_area[1];
        }
        $carts_result=$cart->getList($arr);
        $data['result_carts']=$carts_result;
        if(empty($data['result_carts'])){
            redirect('cart')->with('error','选择一件商品');
        }
        if($_POST){
            try{
                DB::beginTransaction();
                $carts_moneys=$cart->getMoneys($carts_result);
                foreach ($carts_result as $seller_id=>$carts){
                    $arr_seller_id=explode('_',$seller_id);
                    $order=new Order();
                    $order_sn=time().rand(10000,99999);
                    $order->site_id=$this->site->id;
                    $order->order_sn=$order_sn;
                    $order->buyer_id=$user_id;
                    $order->buyer_name=$this->username;
                    $order->seller_id=$arr_seller_id[0];
                    $order->supply_user_id=$arr_seller_id[1];
                    $order->buyer_remark=$request->post('buyer_remark');
                    foreach ($carts as $cart){
                        if($cart->quantity<=0){
                            throw  new \Exception("购买数量不能为零！");
                        }
                        $goods=$cart->Goods;
                        if($goods->stock_count==0){
                            throw  new \Exception("己卖完了！");
                        }
                        if($cart->quantity > $goods->stock_count){
                            throw  new \Exception("库存不足，仅剩{$goods->stock_count}件！");
                        }
                        //减少库存
                        $goods->setStockCount(-$cart->quantity,$cart->spec_id);

                        $orderGoods=new OrderGoods();
                        $orderGoods->order_sn=$order_sn;
                        $orderGoods->goods_id=$goods->id;
                        $orderGoods->supply_goods_id=$goods->supply_goods_id;
                        $orderGoods->supply_user_id=$goods->supply_user_id;
                        $orderGoods->goods_name=$goods->name;
                        $orderGoods->goods_image=$goods->image_url;
                        $orderGoods->spec_id=$cart->spec_id;
                        $orderGoods->quantity=$cart->quantity;
                        $orderGoods->shipping_fee=$cart->shipping_fee;
                        $orderGoods->price=$goods->price;
                        if($goods->supply_goods_id==0){
                            $orderGoods->supply_price=0;
                        }else{
                            $orderGoods->supply_price=math($goods->price,$goods->retail_float_money,'-',2);
                        }
                        $orderGoods->spec_1=$goods->spec_1;
                        $orderGoods->spec_2=$goods->spec_2;
                        $orderGoods->save();
                        //从购物车里删除
                        $cart->delete();
                    }
                    $order->goods_money=$carts_moneys[$seller_id]['goodsPrice'];
                    $order->supply_goods_money=$carts_moneys[$seller_id]['supplyGoodsPrice'];
                    $order->shipping_fee=$carts_moneys[$seller_id]['shippingFee'];
                    $order->order_money=$carts_moneys[$seller_id]['total'];
                    $order->status=1;
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
                }
                DB::commit();
            }catch(\Exception $e){
                $error=$e->getMessage();
                redirect()->back()->with('error',$error);
                DB::rollBack();
            }
            redirect("/member/order/?st_uid={$this->st_uid}")->with('msg','提交成功，请支付！');
        }else{
            $data['cart_id']=implode(',',$cart_id);
            $data['address']=$address;
            $data['addressList']=$address->where('user_id=?')->bindValues($this->user_id)->get();
            $this->view('order',$data);
        }
    }
}
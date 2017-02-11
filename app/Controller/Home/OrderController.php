<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/25
 * Time: 15:38
 */

namespace App\Controller\Home;


use App\Model\Cart;
use App\Model\Goods;
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

    //确认订单
    public function confirm(Request $request,Cart $cart,UserAddress $address)
    {
        $user_id=$this->user_id;
        $cart_id=$request->cart_id;//array[]
        if(empty($cart_id)){
            redirect()->back()->with('error','至少选择一件商品');
        }
        $address_id=(int)$request->get('address_id');
        if($address_id==0){
            $address=$address->where('user_id=? and is_default=1')->bindValues($this->user_id)->first();
        }else{
            $address=$address->where('user_id=? and id='.$address_id)->bindValues($this->user_id)->first();
        }
        $arr=array(
            'buyer_id'=>$this->user_id,
            'cart_id'=>$cart_id
        );
        $carts_result=$cart->getList($arr);
        $data['result_carts']=$carts_result;
        if($_POST){
            try{
                DB::beginTransaction();
                if(!$address->is_exist){
                    throw  new \Exception("请填写收货地址！");
                }
                $goods=new Goods();
                foreach ($carts_result as $seller_id=>$carts){
                    $order=new Order();
                    $order_sn=time().rand(10000,99999);
                    $order->order_sn=$order_sn;
                    $order->buyer_id=$user_id;
                    $order->buyer_name=$this->username;
                    $order->seller_id=$seller_id;
                    $order->buyer_remark=$request->post('buyer_remark');
                    $goods_money=0;
                    $shipping_fee=0;
                    foreach ($carts as $cart){
                        $goods=$goods->find($cart->goods_id);
                        $stock_count=$goods->stock_count;
                        $price=$goods->price;
                        $shipping_fee=math($shipping_fee,$goods->shipping_fee,'+',2);
                        //减少库存
                        if($goods->is_exist){
                            $goods->stock_count=$goods->stock_count-$cart->quantity;
                            $goods->sale_count=$goods->sale_count+$cart->quantity;
                            $goods->save();
                        }
                        if($goods->is_have_spec){
                            $spec=(new GoodsSpec())->find($cart->spec_id);
                            if($spec->goods_id==$goods->id){
                                $spec_1=$spec->spec_1;
                                $spec_2=$spec->spec_2;
                                $stock_count=$spec->stock_count;
                                $price=$spec->price;
                                //规格的库存
                                $spec->stock_count=$spec->stock_count-$cart->quantity;
                                $spec->save();
                            }else{
                                $stock_count=0;
                                $price=0;
                            }
                        }
                        if($stock_count==0){
                            throw  new \Exception("己卖完了！");
                        }
                        if($cart->quantity > $stock_count){
                            throw  new \Exception("库存不足，仅剩{$stock_count}件！");
                        }
                        $orderGoods=new OrderGoods();
                        $orderGoods->order_sn=$order_sn;
                        $orderGoods->goods_id=$goods->id;
                        $orderGoods->goods_name=$goods->name;
                        $orderGoods->goods_image=$goods->image_url;
                        $orderGoods->spec_id=$cart->spec_id;
                        $orderGoods->quantity=$cart->quantity;
                        $orderGoods->price=$price;
                        $orderGoods->spec_1=$spec_1;
                        $orderGoods->spec_2=$spec_2;
                        $orderGoods->save();
                        $goods_money=math($goods_money,math($goods->price,$cart->quantity,'*',2),'+',2);

                        $cart->delete();
                    }
                    $order->goods_money=$goods_money;
                    $order->shipping_fee=$shipping_fee;
                    $order->order_money=math($order->goods_money,$order->shipping_fee,'+',2);
                    $order->status=1;
                    $order->save();
                    
                    $shipping=new OrderShipping();
                    $shipping->order_sn=$order_sn;
                    $shipping->name=$address->name;
                    $shipping->phone=$address->phone;
                    $shipping->region_name=$address->region_name;
                    $shipping->address=$address->address;
                    $shipping->save();
                }

                DB::commit();
            }catch(\Exception $e){
                $error=$e->getMessage();
                redirect()->back()->with('error',$error);
                DB::rollBack();
            }
            redirect('/member/order')->with('msg','提交成功，请支付！');
        }else{
            $data['cart_id']=implode(',',$cart_id);
            $data['address']=$address;
            $this->view('order',$data);
        }
    }
}
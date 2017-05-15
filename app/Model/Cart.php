<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/24
 * Time: 18:01
 */

namespace App\Model;

class Cart extends Model
{
    protected $table='cart';
    public function __construct()
    {
        parent::__construct();
    }
    
    public function add($data=array())
    {
        $buyer_id=(int)$data['buyer_id'];
        $goods_id=(int)$data['goods_id'];
        $spec_id=(int)$data['spec_id'];
        $quantity=(int)$data['quantity'];
        $goods=(new Goods())->findOrFail($goods_id);
        $goods=$goods->pullSupplyGoods();
        if($goods->is_have_spec==1 && $spec_id==0){
            return $this->returnError('请选择规格！');
        }
        if($quantity==0){
            return $this->returnError('请选择数量！');
        }
        if($goods->status==2){
            return $this->returnError('该商品己下架！');
        }
        $goods=$goods->addSpec($spec_id);
        $stock_count=$goods->stock_count;
        if($stock_count<$quantity){
            return $this->returnError("库存不足，仅剩{$stock_count}件！");
        }
        $session_id='';
        if($buyer_id==0){
            $session_id=session_id();
            $cart=$this->where("buyer_id=0 and session_id='$session_id' and goods_id={$goods_id} and spec_id={$spec_id}")->first();
        }else{
            $cart=$this->where("buyer_id={$buyer_id} and goods_id={$goods_id} and spec_id={$spec_id}")->first();
        }
        if($cart->is_exist){
            if((int)$data['is_direct_buy']==1){
                $cart->quantity=$quantity;
            }else{
                $cart->quantity=$cart->quantity + $quantity;
            }
            $cart->save();
            return $this->returnSuccess(array('cart_id'=>$cart->id));
        }else{
            $cart->buyer_id=$buyer_id;
            $cart->seller_id=$goods->user_id;
            $cart->supply_user_id=$goods->supply_user_id;
            $cart->goods_id=$goods->id;
            $cart->goods_name=$goods->name;
            $cart->goods_image=$goods->image_url;
            $cart->spec_id=$spec_id;
            $cart->quantity=$quantity;
            $cart->session_id=$session_id;
            $cart_id=$this->save(true);
            return $this->returnSuccess(array('cart_id'=>$cart_id));
        }
    }

    ////更新Cart里未登陆时添加的商品
    public function refresh($user_id)
    {
        if($user_id!=0 && $user_id!=''){
            $session_id=session_id();
            $this->where("buyer_id=0 and session_id='$session_id'")->update(array('buyer_id'=>$user_id));
        }
    }
    
    public function getGoodsCount($buyer_id)
    {
        if($buyer_id==0){
            $session_id=session_id();
            $where=" buyer_id=0 and session_id='$session_id' ";
        }else{
            $where=" buyer_id={$buyer_id} ";
        }
        return $this->where($where)->value('sum(quantity)','int');
    }

    public function getList($data=array())
    {
        $buyer_id=(int)$data['buyer_id'];
        $cart_id=$data['cart_id'];//多个id数组

        $cityName=$data['cityName'];

        if($buyer_id==0){
            $session_id=session_id();
            $where=" buyer_id=0 and session_id='$session_id' ";
        }else{
            $where=" buyer_id={$buyer_id} ";
        }
        if(is_array($cart_id)){
            $cart_ids=implode(',',$cart_id);
            $where.=" and id in({$cart_ids})";
        }
        $carts=$this->where($where)->orderBy('seller_id')->get();
        //按店铺分组
        $result_carts=array();
        foreach ($carts as $i=>$cart) {
            $result_carts[$cart->seller_id.'_'.$cart->supply_user_id][]=$cart;
        }
        foreach($result_carts as $seller_id=>$carts){
            foreach ($carts as $i=>$cart) {
                $goods = $cart->Goods();
                $goods=$goods->addSpec($cart->spec_id);//取规格的价格和库存

                $result_carts[$seller_id][$i]->Goods=$goods;
                $result_carts[$seller_id][$i]->spec_1 = $goods->spec_1;
                $result_carts[$seller_id][$i]->spec_2 = $goods->spec_2;
                $result_carts[$seller_id][$i]->price = $goods->price;
                $result_carts[$seller_id][$i]->stock_count = $goods->stock_count;


                if($goods->supply_goods_id==0){
                    $result_carts[$seller_id][$i]->supply_price=0;
                }else{
                    $result_carts[$seller_id][$i]->supply_price=math($goods->price,$goods->retail_float_money,'-',2);
                }
                $result_carts[$seller_id][$i]->is_exist = true;
                if ($cart->spec_id != 0 && $goods->spec_is_exist==false) {
                    $result_carts[$seller_id][$i]->is_exist=false;
                    $result_carts[$seller_id][$i]->quantity=0;
                    $result_carts[$seller_id][$i]->stock_count=0;
                }
                if($cityName!=''){
                    $ship=(new Shipping())->find($goods->shipping_id);
                    $shipping_fee=$ship->getPrice($cityName,$result_carts[$seller_id][$i]->quantity);
                    $result_carts[$seller_id][$i]->shipping_fee = $shipping_fee;
                }
            }
        }
        return $result_carts;
    }

    public function getMoneys($result_carts)
    {
        $return=array();
        foreach($result_carts as $seller_id=>$carts){
            $return[$seller_id]['goodsPrice']=0;
            $return[$seller_id]['supplyGoodsPrice']=0;
            $return[$seller_id]['shippingFee']=0;
            $return[$seller_id]['num']=0;
            foreach ($carts as $i=>$cart) {
                $_goodsPrice=math($cart->price,$cart->quantity,'*',2);
                $return[$seller_id]['goodsPrice']=math($return[$seller_id]['goodsPrice'],$_goodsPrice,'+',2);
                if($result_carts[$seller_id][$i]->supply_price!=0){
                    $_supplyGoodsPrice=math($cart->supply_price,$cart->quantity,'*',2);
                    $return[$seller_id]['supplyGoodsPrice']=math($return[$seller_id]['supplyGoodsPrice'],$_supplyGoodsPrice,'+',2);
                }
                $return[$seller_id]['shippingFee']=math($return[$seller_id]['shippingFee'],$result_carts[$seller_id][$i]->shipping_fee,'+',2);
                $return[$seller_id]['num']+=$cart->quantity;
            }
            $_total=math($return[$seller_id]['goodsPrice'],$return[$seller_id]['shippingFee'],'+',2);
            $return[$seller_id]['total']=math($return[$seller_id]['total'],$_total,'+',2);
            if($return[$seller_id]['total']>=100){
                $is_fullDown=(new Shop())->find($seller_id)->is_fulldown;
                if($is_fullDown){
                    $return[$seller_id]['fullDownMoney']=10;
                    $return[$seller_id]['total']=math($return[$seller_id]['total'],10,'-',2);
                }
            }else{
                $return[$seller_id]['fullDownMoney']=0;
            }
        }
        return $return;
    }

    /**
     * @return Goods
     */
    public function Goods()
    {
        return $this->hasOne('\App\Model\Goods','id','goods_id');
    }

    /**
     * @return \App\Model\GoodsSpec
     */
    public function GoodsSpec()
    {
        return $this->hasOne('\App\Model\GoodsSpec','id','spec_id');
    }
}
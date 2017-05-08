<?php

namespace App\Model;

use App\Center;
use App\Helper;

class Order extends Model
{
    protected $table='order';
    protected $dates=array('created_at','payed_at','shipping_at','canceled_at','finished_at');
    public function __construct()
    {
        parent::__construct();
    }

    //退回库存
    private function backStock()
    {
        $orderGoods=$this->OrderGoods();
        foreach ($orderGoods as $oGoods){
            //添加库存
            $goods=(new Goods())->find($oGoods->goods_id);
            $goods->setStockCount($oGoods->quantity,$oGoods->spec_id);
            /*if($goods->is_exist){
                $num=$oGoods->quantity;
                $goods->stock_count=$goods->stock_count+$num;
                $goods->sale_count=$goods->sale_count-$num;
                $goods->save();
                if($goods->is_have_spec){
                    $spec=(new GoodsSpec())->find($oGoods->spec_id);
                    $spec->stock_count=$spec->stock_count+$num;
                    $spec->save();
                }
            }*/
        }
    }

    public function success($operatorOpenId='')
    {
        if($this->status==3){
            throw new \Exception("异常，请勿重复确认收货！");
        }

        $center=new Center();
        $seller_money=$this->order_money;
        $seller=(new User())->find($this->seller_id);
        $sellerAccount=$center->getUserFunc($seller->openid);
        if($this->supply_user_id!=0){
            //采购的商品
            $supplyer_money=math($this->supply_goods_money,$this->shipping_fee,'+',2);
            $seller_money=math($this->order_money,$supplyer_money,'-',2);
            $supplyer=(new User())->find($this->supply_user_id);
            $supplyerAccount=$center->getUserFunc($supplyer->openid);

            //积分奖励
            $rebate_supply=new RebateList();
            $rebate_supply->user_id=$supplyer->id;
            $rebate_supply->money=$supplyer_money;
            $_money=math($supplyer_money,0.21,'*',2);
            $supplyer_money=math($supplyer_money,$_money,'-',2);
        }
        //商家积分奖励
        $rebate_sell=new RebateList();
        $rebate_sell->user_id=$seller->id;
        $rebate_sell->money=$seller_money;
        $_money=math($seller_money,0.21,'*',2);
        $seller_money=math($seller_money,$_money,'-',2);

        $remark="订单号：{$this->order_sn}";
        $params=array(
            'openid'=>$operatorOpenId,
            'body'=>'',
            'type'=>'order_success',
            'remark'=>$remark,
            'label'=>"order_sn:{$this->order_sn}",
            'data'=>array(
            )
        );
        if($seller_money!=0){
            $sell_log=array(
                'openid'=>$seller->openid,
                'type'=>'order_success',
                'remark'=>$remark,
                'funds_available' =>$seller_money,
                'funds_available_now'=>$sellerAccount->funds_available
            );
            array_push($params['data'],$sell_log);
        }
        if($this->supply_user_id!=0){
            if($this->supply_user_id==$this->seller_id){
                $supplyerAccount->funds_available=math($supplyerAccount->funds_available,$seller_money,'+',2);
            }
            $supply_log=array(
                'openid'=>$supplyer->openid,
                'type'=>'order_success_supply',
                'remark'=>$remark,
                'funds_available' =>$supplyer_money,
                'funds_available_now'=>$supplyerAccount->funds_available
            );
            array_push($params['data'],$supply_log);
        }
        $return=$center->receivables($params);
        if($return===true){
            $this->status=5;
            $this->finished_at=time();
            $this->save();
            $rebate_sell->typeid=1;
            $rebate_sell->label=$params['label'];
            $rebate_sell->status=0;
            $rebate_sell->save();
            if($this->supply_user_id!=0){
                $rebate_supply->typeid=1;
                $rebate_supply->label=$params['label'];
                $rebate_supply->status=0;
                $rebate_supply->save();
            }
        }else{
            throw new \Exception($return);
        }
    }

    /**
     * //更新订单产品状态
     * @param $status
     */
    public function updateOrderGoodsStatus($status){
        (new OrderGoods())->where("order_sn='{$this->order_sn}'")->update(array('status'=>$status));
    }

    public function cancel($user)
    {
        if($this->status==1){    //未支付
            if($user->id==$this->buyer_id){
                $this->backStock();//添加库存
                $this->status=2;
                $this->save();
            }else{
                throw new \Exception('异常');
            }
        }elseif($this->status==3){  //己支付
            if($user->id==$this->seller_id){
                $this->backStock();
                //退款
                $center=new Center();
                $buyer=(new User())->find($this->buyer_id);
                $buyerAccount=$center->getUserFunc($buyer->openid);
                $remark="订单号：{$this->order_sn}";
                $params=array(
                    'openid'=>$user->openid,
                    'body'=>'',
                    'type'=>'order_cancel',
                    'remark'=>$remark,
                    'label'=>"order_sn:{$this->order_sn}",
                    'data'=>array(
                        array(
                            'openid'=>$buyer->openid,
                            'type'=>'order_cancel',
                            'remark'=>$remark,
                            'funds_available' =>'-'.$this->payed_funds,
                            'integral_available' =>'-'.$this->payed_integral,
                            'funds_available_now'=>$buyerAccount->funds_available,
                            'integral_available_now'=>$buyerAccount->integral_available,
                        )
                    )
                );
                $return=$center->receivables($params);
                if($return===true){
                    $this->canceled_at=time();
                    $this->status=2;
                    $this->save();
                }else{
                    throw new \Exception($return);
                }
            }else{
                throw new \Exception('异常');
            }
        }
    }

    public function OrderGoods()
    {
        return $this->hasMany('\App\Model\OrderGoods','order_sn','order_sn');
    }

    /**
     * @return OrderShipping
     */
    public function OrderShipping()
    {
        return $this->hasOne('\App\Model\OrderShipping','order_sn','order_sn');
    }

    /**
     * @return Shop
     */
    public function Shop()
    {
        return $this->hasOne('\App\Model\Shop','user_id','seller_id');
    }

    /**
     * @return Supply
     */
    public function Supply()
    {
        if($this->supply_user_id!=0){
            return $this->hasOne('\App\Model\Shop','user_id','supply_user_id');
        }
    }

    /**
     * @return User
     */
    public function Buyer()
    {
        return $this->hasOne('\App\Model\User','id','buyer_id');
    }
}
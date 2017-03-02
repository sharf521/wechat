<?php

namespace App\Model;

use App\Center;

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
            if($goods->is_exist){
                $num=$oGoods->quantity;
                $goods->stock_count=$goods->stock_count+$num;
                $goods->sale_count=$goods->sale_count-$num;
                $goods->save();
                if($goods->is_have_spec){
                    $spec=(new GoodsSpec())->find($oGoods->spec_id);
                    $spec->stock_count=$spec->stock_count+$num;
                    $spec->save();
                }
            }
        }
    }

    public function success($user)
    {
        if($this->status==3){
            throw new \Exception("异常，请勿重复确认收货！");
        }
        $center=new Center();
        $seller=(new User())->find($this->seller_id);
        $sellerAccount=$center->getUserFunc($seller->openid);
        $remark="订单号：{$this->order_sn}";
        $params=array(
            'openid'=>$user->openid,
            'body'=>'',
            'type'=>'order_success',
            'remark'=>$remark,
            'label'=>"order_sn:{$this->order_sn}",
            'data'=>array(
                array(
                    'openid'=>$seller->openid,
                    'type'=>'order_success',
                    'remark'=>$remark,
                    'funds_available' =>$this->order_money,
                    'funds_available_now'=>$sellerAccount->funds_available
                )
            )
        );
        $return=$center->receivables($params);
        if($return===true){
            $this->status=5;
            $this->save();
        }else{
            throw new \Exception($return);
        }
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
}
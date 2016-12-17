<?php

namespace App\Model;

class Order extends Model
{
    protected $table='order';
    protected $dates=array('created_at','payed_at','shipping_at','finished_at');
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


    public function cancel($user_id)
    {
        if($this->status==1){    //未支付
            if($user_id==$this->buyer_id){
                $this->backStock();
                $this->status=2;
                $this->save();
            }else{
                throw new \Exception('异常');
            }
        }elseif($this->status==3){  //己支付
            if($user_id==$this->seller_id){
                $this->backStock();
                //退款
                $this->status=2;
                $this->save();
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